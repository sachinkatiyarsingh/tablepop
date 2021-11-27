import { Component, OnInit } from '@angular/core';
import { OwlOptions } from 'ngx-owl-carousel-o';
import { Location } from '@angular/common';
import { ActivatedRoute, ParamMap, Router } from '@angular/router';
import { ServiceBudgetService } from '../../services/services-budget.service';
import { AuthService } from '../../services/auth.service';
import { AlertService } from 'src/app/services/alert.service';
import { FormBuilder, FormGroup, Validators } from "@angular/forms";
@Component({
    selector: 'app-planner-detail',
    templateUrl: './planner-detail.component.html',
    styleUrls: ['./planner-detail.component.css']
})
export class AppPlannerDetailComponent implements OnInit {
    customOptions: OwlOptions = {
        loop: false,
        items: 1,
        margin: 10,
        nav: false,
        dots: true,
    };
    plannerId: any;
    questionareId: any;
    plannerData: any;
    reviewAlready: boolean = false;
    currentUser: any;
    commentForm: FormGroup;
    ratingVal: any = 0;
    isEditReview: boolean = false;
    selectedReview: any;
    ratingArr = [{ index: 1, active: false }, { index: 2, active: false }, { index: 3, active: false }, { index: 4, active: false }, { index: 5, active: false }];
    constructor(private route: ActivatedRoute, private router: Router, private location: Location, private serviceBudgetService: ServiceBudgetService, private alertService: AlertService, private authService: AuthService, private fb: FormBuilder) {
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
        this.currentUser = this.authService.getCurrentUser();
        this.initCommentForm()
    }
    getPlannerDetail() {
        this.serviceBudgetService.questionnairePlannerDetail(this.plannerId).subscribe((res: any) => {
            if (res.status) {
                this.plannerData = res.data;
                this.reviewAlready = res.reviewAlready || false;
                this.plannerData.review.forEach(element => {
                    var rating = [{ index: 1, active: false }, { index: 2, active: false }, { index: 3, active: false }, { index: 4, active: false }, { index: 5, active: false }];
                    for (var r = 1; r <= element.rating; r++) {
                        rating[r - 1].active = true;
                    }
                    element.reviewRating = rating;
                });

            }
        })
    }
    backToPlanner() {
        this.location.back();
    }
    goToPlan() {
        this.router.navigate(['/planner', this.plannerId, this.questionareId, 'plans']);
    }
    goToPayment(data: any) {
        this.router.navigate(['/payment', data.id, this.questionareId]);
    }
    goToDetail(id: any) {
        this.router.navigate(['/blog/detail', id])
    }
    setRating(val) {
        this.ratingVal = val;
        this.commentForm.get('rating').setValue(this.ratingVal);
        this.ratingArr.forEach(el => {
            if (el.index <= val) {
                el.active = true;
            } else {
                el.active = false;
            }
        })
        //this.rating = [{ index: 1, active: false }, { index: 2, active: false }, { index: 3, active: false }, { index: 4, active: false }, { index: 5, active: false }];

    }
    initCommentForm() {
        this.commentForm = this.fb.group({
            comment: ['', Validators.required],
            rating: ['', Validators.required],
            sellerId: [this.plannerId]
        })
    }
    editReview(data: any) {
        this.selectedReview = data;
        this.ratingArr = data.reviewRating;
        this.commentForm.get('comment').setValue(data.comment);
        this.commentForm.get('rating').setValue(data.rating);
        this.isEditReview = true;
    }
    submitReview() {
        if (this.commentForm) {
            if (this.isEditReview) {
                const data = { ...this.commentForm.value };
                delete data.sellerId;
                data["id"] = this.selectedReview.id;
                this.serviceBudgetService.editReview(data).subscribe((res: any) => {
                    if (res.status) {
                        this.isEditReview = false;
                        this.selectedReview = undefined;
                        this.alertService.success(res.message);
                        this.ratingVal = 0;
                        this.commentForm.get('comment').setValue('');
                        this.getPlannerDetail();
                    } else {
                        this.alertService.error(res.message);
                    }
                })
            }
            else {
                const data = { ...this.commentForm.value };
                this.serviceBudgetService.submitReview(data).subscribe((res: any) => {
                    if (res.status) {
                        this.alertService.success(res.message);
                        this.ratingVal = 0;
                        this.commentForm.get('comment').setValue('');
                        this.getPlannerDetail();
                    } else {
                        this.alertService.error(res.message);
                    }
                })
            }
        }
    }
}
