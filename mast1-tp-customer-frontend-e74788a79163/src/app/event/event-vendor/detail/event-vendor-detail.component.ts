import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';
import { OwlOptions } from 'ngx-owl-carousel-o';
import { Location } from '@angular/common';
import { ActivatedRoute, ParamMap, Router } from '@angular/router';
import { ServiceBudgetService } from '../../../services/services-budget.service';
import { AuthService } from '../../../services/auth.service';
import { AlertService } from 'src/app/services/alert.service';
import { FormBuilder, FormGroup, Validators } from "@angular/forms";
@Component({
    selector: 'app-event-vendor-detail',
    templateUrl: './event-vendor-detail.component.html',
    styleUrls: ['./event-vendor-detail.component.css']
})
export class AppEventVendorDetailComponent implements OnInit {
    customOptions: OwlOptions = {
        loop: false,
        items: 1,
        margin: 10,
        nav: false,
        dots: false,
        navText: ['', '']
    };
    vendorId: any;
    eventId: any;
    commentForm: FormGroup;
    vendorData: any;
    noRecord: any;
    reviewAlready: boolean = false;
    currentUser: any;
    ratingVal: any = 0;
    isEditReview: boolean = false;
    selectedReview: any;
    ratingArr = [{ index: 1, active: false }, { index: 2, active: false }, { index: 3, active: false }, { index: 4, active: false }, { index: 5, active: false }];
    constructor(private alertService: AlertService, private authService: AuthService, private route: ActivatedRoute, private fb: FormBuilder, private router: Router, private location: Location, private serviceBudgetService: ServiceBudgetService) {
        this.route.paramMap.subscribe((param: ParamMap) => {
            this.eventId = param.get('id');
            this.vendorId = param.get('vid');
            if (this.eventId <= 0 || this.vendorId <= 0) {
                this.router.navigate(['/']);
            }
            else {
                this.getVendorDetail();
            }
        });
    }

    ngOnInit(): void {
        this.alertService.isShowHeader(true);
        this.alertService.isShowFooter(true);
        this.alertService.setHeaderClass('light');
        this.currentUser = this.authService.getCurrentUser();
        this.initCommentForm()
    }
    getVendorDetail() {
        this.serviceBudgetService.getVendorDetail(this.vendorId).subscribe((res: any) => {
            if (res.status) {
                this.vendorData = res.data;
                this.reviewAlready = res.reviewAlready || false;
                this.vendorData.review.forEach(element => {
                    var rating = [{ index: 1, active: false }, { index: 2, active: false }, { index: 3, active: false }, { index: 4, active: false }, { index: 5, active: false }];
                    for (var r = 1; r <= element.rating; r++) {
                        rating[r - 1].active = true;
                    }
                    element.reviewRating = rating;
                });
            }
        })
    }
    backToVendor() {
        this.location.back();
    }
    goToProduct() {
        this.router.navigate(['/event', this.eventId, 'vendor', this.vendorId, 'product']);
    }
    goToBlogDetail(id: any) {
        this.router.navigate(['/blog/detail', id])
    }
    viewServices(data: any) {
        this.router.navigate(['/viewservices'])
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
            sellerId: [this.vendorId]
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
                        this.getVendorDetail();
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
                        this.getVendorDetail();
                    } else {
                        this.alertService.error(res.message);
                    }
                })
            }
        }
    }
}
