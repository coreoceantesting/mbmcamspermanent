<?php

namespace App\Models;

use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveRequestHierarchy extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'clas_id',
        'requester_designation_id',
        'requester_department_id',

        '1_approver_designation_id',
        '1_approver_department_id',

        '2_approver_designation_id',
        '2_approver_department_id',


        '3_approver_designation_id',
        '3_approver_department_id',

        '4_approver_designation_id',
        '4_approver_department_id',
    ];

    protected $appends = [
        'first_approver_department','first_approver_designation',
        'second_approver_department','second_approver_designation',
        'third_approver_department','third_approver_designation',
        'fourth_approver_department','fourth_approver_designation'

    ];
    public function getFirstApproverDepartmentAttribute(){
        return $this->attributes['1_approver_department_id'];
    }
    public function getFirstApproverDesignationAttribute(){
        return $this->attributes['1_approver_designation_id'];
    }


    public function getSecondApproverDepartmentAttribute(){
        return $this->attributes['2_approver_department_id'];
    }
    public function getSecondApproverDesignationAttribute(){
        return $this->attributes['2_approver_designation_id'];
    }
    public function getThirdApproverDepartmentAttribute(){
        return $this->attributes['3_approver_department_id'];
    }
    public function getThirdApproverDesignationAttribute(){
        return $this->attributes['3_approver_designation_id'];
    }

    public function getFourthApproverDepartmentAttribute(){
        return $this->attributes['4_approver_department_id'];
    }
    public function getFourthApproverDesignationAttribute(){
        return $this->attributes['4_approver_designation_id'];
    }



    public function requesterClass()
    {
        return $this->hasOne(Clas::class, 'id','clas_id');
    }

    public function requesterDepartment()
    {
        return $this->hasOne(Department::class, 'id','requester_department_id');
    }

    public function requesterDesignation()
    {
        return $this->hasOne(Designation::class, 'id','requester_designation_id');
    }



    public function firstApproverDepartments()
    {
        return $this->hasOne(Department::class, 'id','1_approver_department_id');
    }

    public function firstApproverDesignations()
    {
        return $this->hasOne(Designation::class, 'id','1_approver_designation_id');
    }



    public function secondApproverDepartments()
    {
        return $this->hasOne(Department::class, 'id','2_approver_department_id');
    }

    public function secondApproverDesignations()
    {
        return $this->hasOne(Designation::class, 'id','2_approver_designation_id');
    }



    public function thirdApproverDepartments()
    {
        return $this->hasOne(Department::class, 'id','3_approver_department_id');
    }

    public function thirdApproverDesignations()
    {
        return $this->hasOne(Designation::class, 'id','3_approver_designation_id');
    }



    public function fourthApproverDepartments()
    {
        return $this->hasOne(Department::class, 'id','4_approver_department_id');
    }

    public function fourthApproverDesignations()
    {
        return $this->hasOne(Designation::class, 'id','4_approver_designation_id');
    }












}
