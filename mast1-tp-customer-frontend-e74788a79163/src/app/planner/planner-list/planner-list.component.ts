import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, ParamMap, Router } from '@angular/router';
import { ServiceBudgetService } from '../../services/services-budget.service';
import { AlertService } from '../../services/alert.service';

@Component({
    selector: 'app-planner-list',
    templateUrl: './planner-list.component.html',
    styleUrls: ['./planner-list.component.css']
})
export class AppPlannerListComponent implements OnInit {
    questionareId: any;
    plannerList: any[] = [];
    constructor(private route: ActivatedRoute, private router: Router, private alertService: AlertService, private serviceBudgetService: ServiceBudgetService) {
        this.route.paramMap.subscribe((param: ParamMap) => {
            this.questionareId = param.get('id');
            this.getPlannerList();
        });
    }

    ngOnInit(): void {
        this.alertService.isShowHeader(true);
        this.alertService.isShowFooter(true);
        this.alertService.setHeaderClass('light');
    }

    getPlannerList() {
        this.serviceBudgetService.questionnairePlanner(this.questionareId).subscribe((res: any) => {
            if (res.status) {
                this.plannerList = res.data || [];
                this.plannerList.forEach(element => {
                    var rating = [{ index: 1, active: false }, { index: 2, active: false }, { index: 3, active: false }, { index: 4, active: false }, { index: 5, active: false }];
                    for (var r = 1; r <= element.rating; r++) {
                        rating[r - 1].active = true;
                    }
                    element.reviewRating = rating;
                });
            }
        });
    }
    goToDetail(data: any) {
        this.router.navigate(['/planner', data.id, this.questionareId, 'details'])
    }

}
