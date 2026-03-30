<?php

namespace Controllers;

use Debug\DebugTools;
use Model\Accrual;
use Model\Deduction;
use Model\Employee;
use Model\User;
use Src\Validator\Validator;
use Src\View;
use Src\Request;
use Src\Auth\Auth;

class Site
{
    public function index(Request $request): string
    {
        $fio = Employee::findIdentity(Auth::user()->employee_id)->getFullName();
        return (new View())->render('site.hello', ['login' => $fio]);
    }

    public function admin(Request $request): string
    {
        $search = $request->get('search');
        $financists = array_filter(
            User::whereHas('role', function ($query) {
                $query->where('name', 'financist');
            })->get()->toArray(),
            function ($user) use ($search) {
                return !$search || str_contains($user['login'], $search);
            }
        );

        $freeEmployees = Employee::whereNotIn('id', array_map(function ($item) {
            return ($item['employee_id']);
        }, User::all()->toArray()))->get();

        $message = '';
        $errors = [];

        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'login' => ['required', 'unique:users,login'],
                'password' => ['required'],
                'employee_id' => ['required', 'unique:users,employee_id'],
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
            }
            elseif (User::create(array_merge($request->all(), ['role_id' => 2]))) {
                $message = 'Пользователь успешно создан';
            }
        }

        return (new View())->render('site.admin', [
            'financists' => $financists,
            'employees' => $freeEmployees,
            'message' => $message,
            'errors' => $errors,
            'search' => $search,
        ]);
    }

    public function deleteFinancist(Request $request): string
    {
        if($request->method === 'POST') {
            User::destroy($request->get('id'));
            app()->route->redirect('admin');
        }
        return (new View())->render('site.confirmDelete', [
            'name' => 'бухгалтера',
            'target' => Employee::findIdentity(User::find($request->get('id'))->employee_id)->getFullName(),
            'rollback' => app()->route->getUrl('admin')
        ]);
    }

    public function editFinancist(Request $request): string
    {
        $user_id = $request->get('id');
        $errors = [];
        if($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'login' => ['required', 'unique:users,login'],
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
            }
            else {
                $financist = User::find($user_id);
                $financist->login = $request->get('login');
                $financist->save();
                app()->route->redirect('admin');
            }
        }
        return (new View())->render('site.updateFinancist', [
            'target' => Employee::find(User::find($user_id)->employee_id)->getFullName(),
            'id' => $user_id,
            'errors' => $errors
        ]);
    }

    public function financist(Request $request): string
    {
        $employees = Employee::all();
        return (new View())->render('site.financist', [
            'employees' => $employees,
        ]);
    }

    public function financistStats(Request $request): string
    {
        $employee_id = $request->get('id');
        $period = (int)$request->get('period') > 0 ? (int)$request->get('period') : 1;
        $employee = Employee::find($employee_id);
        $salary = $employee->getSalary($period);
        $transactions = [];
        $accruals = $employee->getAccruals();
        $deductions = $employee->getDeductions();
        $sum = 0;

        for ($i = 0; $i < $period; $i++) {
            $begin = strtotime('+'.$i . ' month');
            $month = date('F', $begin) . "\n";
            $sum += (int)$salary;
            $transactions[] = [
                'month' => $month,
                'name' => 'Заработная плата',
                'result' => $salary
            ];

            foreach ($accruals as $accrual) {
                if ($i < $accrual->month_left) {
                    $sum += (int)$accrual->amount;
                    $transactions[] = [
                        'month' => $month,
                        'name' => $accrual->name,
                        'result' => $accrual->amount
                    ];
                }
            }

            foreach ($deductions as $deduction) {
                if ($i < $deduction->month_left) {
                    $sum -= (int)$deduction->amount;
                    $transactions[] = [
                        'month' => $month,
                        'name' => $deduction->name,
                        'result' => '-'.$deduction->amount
                    ];
                }
            }
        }

        return (new View())->render('site.financistStats', [
            'period' => $period,
            'employee' => $employee,
            'salary' => $salary,
            'transactions' => array_reverse($transactions),
            'sum' => $sum
        ]);
    }

    public function financistAccruals(Request $request): string
    {
        $employee_id = $request->get('id');
        $employee = Employee::find($employee_id);
        $accruals = $employee->getAccruals();
        $sum = 0;
        foreach ($accruals as $accrual) {
            $sum += (int)$accrual->amount;
        }

        return (new View())->render('site.financistTransactions', [
            'employee' => $employee,
            'transactions' => $accruals,
            'sum' => $sum,
            'entities' => 'accruals'
        ]);
    }

    public function addAccrual(Request $request): string
    {
        $employee_id = $request->get('id');
        $employee = Employee::find($employee_id);
        $errors = [];

        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'name' => ['required'],
                'amount' => ['required'],
                'month_left' => ['required']
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
            }
            elseif (Accrual::create($request->all())) {
                app()->route->redirect('financist/accruals?id=' . $employee_id);
            }
        }

        return (new View())->render('site.transactionAddForm', [
            'id' => $employee_id,
            'name' => 'надбавки',
            'employee' => $employee,
            'errors' => $errors
        ]);
    }

    public function editAccrual(Request $request): string
    {
        $accrual_id = $request->get('id');
        $accrual = Accrual::find($accrual_id);
        $employee_id = $accrual->employee_id;
        $employee = Employee::find($employee_id);
        $errors = [];

        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'name' => ['required'],
                'amount' => ['required'],
                'month_left' => ['required']
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
            }
            else {
                $accrual->update($request->all());
                app()->route->redirect('financist/accruals?id=' . $employee_id);
            }
        }

        return (new View())->render('site.transactionAddForm', [
            'id' => $employee_id,
            'name' => 'надбавки',
            'employee' => $employee,
            'errors' => $errors,
            'transaction' => $accrual,
        ]);
    }

    public function deleteAccrual(Request $request): string
    {
        $accrual_id = $request->get('id');
        $accrual = Accrual::find($accrual_id);
        $employee = Employee::find($accrual->employee_id);

        if ($request->method === 'POST') {
            Accrual::destroy($accrual_id);
            app()->route->redirect('financist/accruals?id=' . $accrual->employee_id);
        }

        return (new View())->render('site.confirmDelete', [
            'target' => $employee->getFullName(),
            'name' => 'надбавку',
            'rollback' => app()->route->getUrl('financist/accruals?id=' . $accrual->employee_id),
        ]);
    }public function financistDeductions(Request $request): string
    {
        $employee_id = $request->get('id');
        $employee = Employee::find($employee_id);
        $deductions = $employee->getDeductions();

        $sum = 0;
        foreach ($deductions as $deduction) {
            $sum += (int)$deduction->amount;
        }

        return (new View())->render('site.financistTransactions', [
            'employee' => $employee,
            'transactions' => $deductions,
            'sum' => $sum,
            'entities' => 'deductions'
        ]);
    }

    public function addDeduction(Request $request): string
    {
        $employee_id = $request->get('id');
        $employee = Employee::find($employee_id);
        $errors = [];

        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'name' => ['required'],
                'amount' => ['required'],
                'month_left' => ['required']
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
            }
            elseif (Deduction::create($request->all())) {
                app()->route->redirect('financist/deductions?id=' . $employee_id);
            }
        }

        return (new View())->render('site.transactionAddForm', [
            'id' => $employee_id,
            'name' => 'вычета',
            'employee' => $employee,
            'errors' => $errors
        ]);
    }

    public function editDeduction(Request $request): string
    {
        $deduction_id = $request->get('id');
        $deduction = Deduction::find($deduction_id);
        $employee_id = $deduction->employee_id;
        $employee = Employee::find($employee_id);
        $errors = [];

        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'name' => ['required'],
                'amount' => ['required'],
                'month_left' => ['required']
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
            }
            else {
                $deduction->update($request->all());
                app()->route->redirect('financist/deductions?id=' . $employee_id);
            }
        }

        return (new View())->render('site.transactionAddForm', [
            'id' => $employee_id,
            'name' => 'вычета',
            'employee' => $employee,
            'errors' => $errors,
            'transaction' => $deduction,
        ]);
    }

    public function deleteDeduction(Request $request): string
    {
        $deduction_id = $request->get('id');
        $deduction = Accrual::find($deduction_id);
        $employee = Employee::find($deduction->employee_id);

        if ($request->method === 'POST') {
            Accrual::destroy($deduction_id);
            app()->route->redirect('financist/deductions?id=' . $deduction->employee_id);
        }

        return (new View())->render('site.confirmDelete', [
            'target' => $employee->getFullName(),
            'name' => 'вычет',
            'rollback' => app()->route->getUrl('financist/deductions?id=' . $deduction->employee_id),
        ]);
    }

    public function login(Request $request): string
    {
        $errors = [];
        //Если просто обращение к странице, то отобразить форму
        if ($request->method === 'GET') {
            return new View('site.login');
        }
        else {
            $validator = new Validator($request->all(), [
                'login' => ['required'],
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
            }
            elseif (Auth::attempt($request->all())) {
                app()->route->redirect('');
            }
        }
        //Если аутентификация не удалась, то сообщение об ошибке
        return new View('site.login', [
            'message' => 'Неправильные логин или пароль',
            'errors' => $errors
        ]);
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('login');
    }
}