import { Component, OnInit } from '@angular/core';
import { OwlOptions } from 'ngx-owl-carousel-o';
import { Location } from '@angular/common';
import { ActivatedRoute, ParamMap, Router } from '@angular/router';
import { ServiceBudgetService } from '../../services/services-budget.service';
import { AuthService } from '../../services/auth.service';
import { AlertService } from 'src/app/services/alert.service';
import { FormBuilder, FormGroup, Validators } from "@angular/forms";
@Component({
  selector: 'app-planner-details',
  templateUrl: './planner-details.component.html',
  styleUrls: ['./planner-details.component.css']
})
export class PlannerDetailsComponent implements OnInit {

  customOptions: OwlOptions = {
    loop: false,
    items: 1,
    margin: 10,
    nav: false,
    dots: false,
    navText: ['', '']
  };
  ratingVal: any = 0;
  commentForm: FormGroup;
  sellerId: any;
  questionareId: any;
  plannerData: any;
  reviewAlready: boolean = false;
  currentUser: any;
  isEditReview: boolean = false;
  selectedReview: any;
  ratingArr = [{ index: 1, active: false }, { index: 2, active: false }, { index: 3, active: false }, { index: 4, active: false }, { index: 5, active: false }];
  constructor(private authService: AuthService, private fb: FormBuilder, private route: ActivatedRoute, private router: Router, private location: Location, private serviceBudgetService: ServiceBudgetService, private alertService: AlertService) {
    this.route.paramMap.subscribe((param: ParamMap) => {
      this.questionareId = param.get('id');
      this.sellerId = param.get('id');
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
    this.serviceBudgetService.questionnairePlannerDetail(this.questionareId).subscribe((res: any) => {
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
  goToDetail(id: any) {
    this.router.navigate(['/blog/detail', id])
  }
  backToPlanner() {
    this.location.back();
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
      sellerId: [this.sellerId]
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
