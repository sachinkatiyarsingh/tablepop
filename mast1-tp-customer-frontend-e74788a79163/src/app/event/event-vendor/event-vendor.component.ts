import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, ParamMap, Router } from '@angular/router';
import { ServiceBudgetService } from '../../services/services-budget.service';
import { AlertService } from '../../services/alert.service';

@Component({
    selector: 'app-event-vendor-list',
    templateUrl: './event-vendor.component.html',
    styleUrls: ['./event-vendor.component.css']
})
export class AppEventVendorComponent implements OnInit {
    eventId: any;
    vendorList: any;
    totalPage: any;
    pageOpts = {
        PageNumber: 1,
        PageSize: 10
    };
    constructor(private route: ActivatedRoute, private alertService: AlertService, private router: Router, private serviceBudgetService: ServiceBudgetService) {
        this.route.paramMap.subscribe((param: ParamMap) => {
            this.eventId = param.get('id');
            if (this.eventId <= 0) {
                this.router.navigate(['/']);
            }
            else {
                this.getVendorList();
            }
        });
    }

    ngOnInit(): void {
        this.alertService.isShowHeader(true);
        this.alertService.isShowFooter(true);
        this.alertService.setHeaderClass('light');
    }

    getVendorList() {
        var req = {
            pageNo: this.pageOpts.PageNumber,
            PageSize: this.pageOpts.PageSize,
            eventId: this.eventId
        }
        this.serviceBudgetService.vendorList(req).subscribe((res: any) => {
            if (res.status) {
                this.vendorList = res.data ? res.data.vendors : [];
                this.totalPage = res.data ? res.data.totalPage : 0;
                if (this.vendorList) {
                    this.vendorList.forEach(element => {
                        var rating = [{ index: 1, active: false }, { index: 2, active: false }, { index: 3, active: false }, { index: 4, active: false }, { index: 5, active: false }];
                        for (var r = 1; r <= element.rating; r++) {
                            rating[r - 1].active = true;
                        }
                        element.reviewRating = rating;
                    });
                }
            }
        });
    }
    goToDetail(data: any) {
        this.router.navigate(['/event', this.eventId, 'vendor', data.id]);
    }
    PageSelect(PageNumber: any) {
        this.pageOpts.PageNumber = PageNumber;
        this.getVendorList();
    }

}
