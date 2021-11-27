import { Component, OnInit } from '@angular/core';
import { Location } from '@angular/common';
import { ActivatedRoute, ParamMap, Router } from '@angular/router';
import { ServiceBudgetService } from '../../../services/services-budget.service';
import { OwlOptions } from 'ngx-owl-carousel-o';
import { AlertService } from '../../../services/alert.service';
@Component({
    selector: 'app-event-vendor-product',
    templateUrl: './event-vendor-product.component.html',
    styleUrls: ['./event-vendor-product.component.css']
})
export class AppEventVendorProductComponent implements OnInit {
    vendorId: any;
    eventId: any;
    products: any[] = [];
    totalPage: any;
    pageOpts = {
        PageNumber: 1,
        PageSize: 10
    };
    customOptions: OwlOptions = {
        loop: false,
        items: 1,
        margin: 10,
        nav: false,
        dots: false,
        navText: ['', '']
    };
    productImages: boolean = false;
    product: any;
    constructor(private route: ActivatedRoute, private router: Router, private alertService: AlertService, private location: Location, private serviceBudgetService: ServiceBudgetService) {
        this.route.paramMap.subscribe((param: ParamMap) => {
            this.vendorId = param.get('vid');
            this.eventId = param.get('id');
            this.getVendorProduct();
        });
    }

    ngOnInit(): void {
        this.alertService.isShowHeader(true);
        this.alertService.isShowFooter(true);
        this.alertService.setHeaderClass('light');
    }
    getVendorProduct() {
        var req = {
            pageNo: this.pageOpts.PageNumber,
            PageSize: this.pageOpts.PageSize,
            vendorId: this.vendorId
        }
        this.serviceBudgetService.getVendorProduct(req).subscribe((res: any) => {
            if (res.status) {
                this.products = res.data ? res.data.product : [];
                this.totalPage = res.data ? res.data.totalPage : 0;
            }
        })
    }
    goToPayment(data: any) {
        this.router.navigate(['/event', this.eventId, 'product', data.id, 'payment']);
    }
    productImageShow(data: any) {
        this.productImages = true;
        this.serviceBudgetService.productById(data.id).subscribe((res: any) => {
            if (res.status) {
                this.product = res.data;
            }
        })
    }
}
