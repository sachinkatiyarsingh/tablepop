<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    protected $fillable = [
        'latitude','longitude','helpedBudgetOther','themeEventOther','farEventDate','customerId', 'levelOfService','eventPlanning','themeEvent','levelOfServicePlanningType','partyPlanner','budgetRangeStart','budgetRangeEnd','helpedBudget','premiumEvent','confirmationPartyPlanner','name','email','mobile','eventName','typeEvent','guestExpectStart','guestExpectEnd','farEvent','partyPlaningService','vennu','addPhotos','weddindIdeas','anytningPartyPlanner','status'
    ];
}
