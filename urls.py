from django.urls import path
from . import views

urlpatterns = [
    path('', views.dashboard, name='dashboard'),
    path('attendance/', views.attendance, name='attendance'),
    path('add_employees/', views.add_employees, name='add_employees'),
    path('payroll_computations/', views.payroll_computations, name='payroll_computations'),
    path('personnel_profile/', views.personnel_profile, name='personnel_profile'),
]