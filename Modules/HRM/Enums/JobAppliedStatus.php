<?php

namespace Modules\HRM\Enums;

enum JobAppliedStatus: int
{
    case Default = 0;
    case SelectedInterview = 1;
    case SendMailForInterview = 2;
    case InterviewParticipant = 3;
    case FinalSelected = 4;
    case SendMailForOfferLetter = 5;
    case Hired = 6;
    case ConvertToEmployee = 7;
    case Pending = 8;
    case Rejected = 9;
}
