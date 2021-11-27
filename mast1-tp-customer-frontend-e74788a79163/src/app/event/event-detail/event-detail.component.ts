import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, ParamMap } from "@angular/router"
import { eventsService } from '../../services/events.service';
import { customermoodboard } from '../../services/customermoodboard.service';
import { AlertService } from '../../services/alert.service';
import { OwlOptions } from 'ngx-owl-carousel-o';
import { customerOfferService } from '../../services/customer-offer.service';
import { FormBuilder, FormGroup, Validators } from "@angular/forms";
@Component({
    selector: 'app-event-detail',
    templateUrl: './event-detail.component.html',
    styleUrls: ['./event-detail.component.css']
})
export class EventDetailComponent implements OnInit {
    eventId: any;
    eventData: any;
    customOptions: OwlOptions = {
        loop: false,
        items: 1,
        margin: 10,
        nav: false,
        dots: true,
        dotsData: true,
        navText: ['', ''],
    };
    moodboardData: any;
    moodboardAlbum: any;
    moodboardId: any;
    imageId: any;
    showImages: boolean = false;
    status: boolean = false;
    questionnaireId: any;
    readMore: boolean = false
    offerDetail: any[] = [];
    showShortDesciption = true;
    noRecordFound: boolean = false;
    noRecord: boolean = false;
    noMilestone: boolean = true;
    eventShareForm: FormGroup;
    constructor(private fb: FormBuilder, private customerOffer: customerOfferService, private router: Router, private route: ActivatedRoute, private eventService: eventsService, private alertService: AlertService, private customerMoodboard: customermoodboard,) {
        this.route.paramMap.subscribe((param: ParamMap) => {
            this.eventId = param.get('id');
            this.questionnaireId = param.get('id');
            if (this.eventId <= 0) {
                this.router.navigate(['/']);
            }
            else {
                this.getEventDetail();
                this.getMoodBoardData();
            }
        });
    }

    ngOnInit(): void {
        this.alertService.isShowHeader(true);
        this.alertService.isShowFooter(false);
        this.alertService.setHeaderClass('');
        this.getDetails()
        this.getMoodBoardAlbum(this.moodboardId);
        this.initShareEvent();
    }
    getEventDetail() {
        this.eventService.eventDetail(this.eventId).subscribe((res: any) => {
            if (res.status) {
                this.eventData = res.data;

            }
        })
    }
    goToMilestone(id: any) {
        this.router.navigate(['/milestoneview', id]);
    }
    changestatus(data: any) {
        this.eventService.changeStatus(2, data.id).subscribe((res) => {
            if (res.status) {
                this.alertService.success(res.message, true);
                data.status = 2;
            }
            else {
                this.alertService.error(res.message);
            }
        })
    }
    getMoodBoardData() {
        this.customerMoodboard.moodBoardList(this.eventId).subscribe((res: any) => {
            if (res.status) {
                this.moodboardData = res.data.moodboards;
                if (this.moodboardData != undefined && this.moodboardData.length > 0) {
                    this.getMoodBoardAlbum(this.moodboardData[0].id)
                }
            }
        })
    }
    getMoodBoardAlbum(moodboardId: any) {
        this.customerMoodboard.moodBoardAlbum(moodboardId).subscribe((res: any) => {
            if (res.status) {
                this.imageId = moodboardId;
                this.moodboardAlbum = res.data;
                this.moodboardAlbum.moodboardimage.forEach(element => {
                    element.imgStatus = element.status == 1 ? true : false;
                });
                this.noRecord = this.moodboardAlbum.length != undefined && this.moodboardAlbum.length != null && this.moodboardAlbum.length > 0 ? false : true;
            }
        })
    }
    getThumbnail(img: any) {
        return `<img src="${img}" alt=""> `;
    }
    changesStatus(data) {
        this.customerMoodboard.moodBoardImageSelect(data.imgStatus, data.id).subscribe((res: any) => {
            if (res.status) {
                this.alertService.success(res.message, true);
            }
            else {
                this.alertService.error(res.message);
            }
        })
    }
    getDetails() {
        this.customerOffer.customerEventOffer(this.questionnaireId).subscribe((res: any) => {
            if (res.status) {
                this.offerDetail = res.data || {};
                this.noRecordFound = this.offerDetail.length != undefined && this.offerDetail.length != null && this.offerDetail.length > 0 ? false : true;
                this.offerDetail.forEach(f => {
                    f.isReadmore = false;
                });

            }
        })
    }
    goToPayment(id: any) {
        this.router.navigate(['/offerPayment', id])
    }
    goToTransaction(id: any) {
        this.router.navigate(['/transactions', id])
    }
    initShareEvent() {
        this.eventShareForm = this.fb.group({
            email: ['', [Validators.required, Validators.email]],
        })
    }
    shareEvent() {
        if (this.eventShareForm.valid) {
            const data: any = { ...this.eventShareForm.value };
            let formData: FormData = new FormData();
            formData.append("eventId", this.eventId);
            formData.append("email", data.email);
            this.eventService.shareEvent(formData).subscribe((res: any) => {
                if (res.status) {
                    this.alertService.success(res.message);
                    this.eventShareForm.get('email').setValue('');
                } else {
                    this.alertService.error(res.message);
                }
            })
        }
    }
}
