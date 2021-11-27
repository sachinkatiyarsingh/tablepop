import { Component, OnInit } from '@angular/core';
import { Location } from '@angular/common';
import { ActivatedRoute, ParamMap, Router } from '@angular/router';
import { ServiceBudgetService } from '../../services/services-budget.service';
import { MessageService } from '../../services/message.service';
import { AlertService } from '../../services/alert.service';

@Component({
    selector: 'app-planner-plan',
    templateUrl: './planner-plan.component.html',
    styleUrls: ['./planner-plan.component.css']
})
export class AppPlannerPlanComponent implements OnInit {
    plannerId: any;
    questionareId: any;
    plannerData: any;
    isPlanSelected: boolean = false;
    selectedPlan: any;
    groupId: any;
    isChatShow: boolean = false;
    constructor(private route: ActivatedRoute, private router: Router, private location: Location, private alertService: AlertService, private messageService: MessageService, private serviceBudgetService: ServiceBudgetService) {
        this.route.paramMap.subscribe((param: ParamMap) => {
            this.plannerId = param.get('id');
            this.questionareId = param.get('qid');
            this.getPlannerDetail();
        });
    }

    ngOnInit(): void {
        this.alertService.isShowHeader(true);
        this.alertService.isShowFooter(true);
        this.alertService.setHeaderClass('light');
    }
    getPlannerDetail() {
        this.serviceBudgetService.questionnairePlannerDetail(this.plannerId).subscribe((res: any) => {
            if (res.status) {
                this.plannerData = res.data;
            }
        })
    }
    backToPlanner() {
        this.location.back();
    }
    selectPlan(data: any) {
        this.selectedPlan = data;
        this.isPlanSelected = true;
    }
    goToPayment() {
        if (this.selectedPlan.isCustom == 0) {
            this.router.navigate(['/payment', this.selectedPlan.id, this.questionareId]);
        }
        else {
            let data: any = {
                questionnaireId: this.questionareId,
                planId: this.selectedPlan.id
            }
            this.serviceBudgetService.customerPayment(data).subscribe((res: any) => {
                if (res.status) {
                    if (res.data) {
                        this.messageService.initSocket();
                        this.groupId = res.data.groupId;
                        this.isChatShow = true;
                        setTimeout(() => {
                            this.messageService.sendEvent('new_group', { groupId: this.groupId })
                        })
                    }
                }
            })
        }
    }

}
