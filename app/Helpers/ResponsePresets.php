<?php

namespace App\Helpers;

trait ResponsePresets
{
    public function unauthorized()
    {
        return $this
            ->setErrors('Your session has expired. Please refresh the page.')
            ->setStatus($this->statuses['UNAUTHORIZED_APP'])
            ->get();
    }

    public function unauthorizedUser()
    {
        return $this
            ->setErrors('Your session has expired. Please login again.')
            ->setStatus($this->statuses['UNAUTHORIZED_USER'])
            ->get();
    }

    public function setStatusWrongToken()
    {
        return $this->setStatus($this->statuses['WRONG_RESET_TOKEN']);
    }

    public function setStatusPaymentFailed()
    {
        return $this
            ->setStatus($this->statuses['ERROR'])
            ->setAlerts('Your payment has failed. Please contact customer support via phone or Live Chat.');
    }

    public function setStatusPaymentPending()
    {
        return $this->setStatus($this->statuses['PROCESSING_PAYMENT']);
    }

    public function setStatusPaymentSuccess()
    {
        return $this->setAlerts('Your payment has succeeded. We are working on your order. You will receive an email notificaton once it is ready.');
    }

    public function setStatusBasicError()
    {
        return $this->setStatus($this->statuses['ERROR']);
    }

    public function wrongRole($role)
    {
        return $this->setErrors('Your user is not allowed to access this resource')
            ->setAlerts('Please login as '.$role.' to access this resource')
            ->setStatus($this->statuses['WRONG_ROLE']);
    }

    public function setStatusNotContentOwner()
    {
        return $this
            ->setErrors('Your user is not allowed to access this resource')
            ->setStatus($this->statuses['NOT_PERMITTED_USER']);
    }
}
