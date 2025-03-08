from django.shortcuts import render,redirect
from .forms import EmployeeForm
from .models import Employee

def dashboard(request):
    return render(request, 'ui/dashboard.html')

def attendance(request):
    return render(request, 'ui/attendance.html')

def add_employees(request):
    if request.method == 'POST':
        print(request.POST)  
        employee_id = request.POST.get('employee_id')
        name = request.POST.get('name')
        position = request.POST.get('position')
        classification = request.POST.get('classification')

        Employee.objects.create(
            employee_id=employee_id,
            name=name,
            position=position,
            classification=classification
        )
        return redirect('add_employees')

    return render(request, 'ui/add_employees.html')

def payroll_computations(request):
    return render(request, 'ui/payroll_computations.html')

def personnel_profile(request):
    return render(request, 'ui/personnel_profile.html')