<?php

namespace Modules\HRM\Interface;

interface RecruitmentServiceInterface
{
    public function applicantFilter($request);

    public function selectedForInterview($request);

    public function alreadyMailForInterview($request);

    public function interviewParticipate($request);

    public function applicantFinalSelected($request);

    public function applicantOfferLetter($request);

    public function applicantHiredList($request);

    public function applicantRejectList($request);
}
