<?php

namespace Modules\HRM\Enums;

enum EmployeeExcelColumns: int
{
    case EMP_ID = 0;
    case NAME = 1;
    case PHONE = 2;
    case ALTERNATIVE_PHONE = 3;
    case PHOTO = 4;
    case DOB = 5;
    case NID = 6;
    case BIRTH_CERTIFICATE = 7;
    case ATTACHMENTS = 8;
    case MARITAL_STATUS = 9;
    case GENDER = 10;
    case BLOOD = 11;
    case COUNTRY = 12;
    case FATHER_NAME = 13;
    case MOTHER_NAME = 14;
    case RELIGION = 15;
    case EMAIL = 16;
    case LOGIN_ACCESS = 17;
    case HOME_PHONE = 18;
    case EMERGENCY_CONTACT_PERSON = 19;
    case EMERGENCY_CONTACT_PERSON_PHONE = 20;
    case EMERGENCY_CONTACT_PERSON_RELATION = 21;
    case PRESENT_DIVISION = 22;
    case PRESENT_DISTRICT = 23;
    case PRESENT_UPAZILA = 24;
    case PRESENT_UNION = 25;
    case PRESENT_VILLAGE = 26;
    case PERMANENT_DIVISION = 27;
    case PERMANENT_DISTRICT = 28;
    case PERMANENT_UPAZILA = 29;
    case PERMANENT_UNION = 30;
    case PERMANENT_VILLAGE = 31;
    case SHIFT_ID = 32;
    case DEPARTMENT_ID = 33;
    case SECTION_ID = 34;
    case SUBSECTION_ID = 35;
    case DESIGNATION_ID = 36;
    case GRADE_ID = 37;
    case DUTY_TYPE_ID = 38;
    case JOINING_DATE = 39;
    case EMPLOYEE_TYPE = 40;
    case SALARY = 41;
    case OVERTIME_ALLOWED = 42;
    case STARTING_SHIFT_ID = 43;
    case STARTING_SALARY = 44;
    case EMPLOYMENT_STATUS = 45;
    case RESIGN_DATE = 46;
    case LEFT_DATE = 47;
    case TERMINATION_DATE = 48;
    case BANK_BRANCH_NAME = 49;
    case BANK_NAME = 50;
    case BANK_ACCOUNT_NAME = 51;
    case MOBILE_BANKING_PROVIDER = 52;
    case MOBILE_BANKING_ACCOUNT_NUMBER = 53;
}
