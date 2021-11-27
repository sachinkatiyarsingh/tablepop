import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from "@angular/forms";
import { ActivatedRoute, ParamMap, Router } from '@angular/router';
import { StripeService, Elements, Element as StripeElement, ElementsOptions } from "ngx-stripe"
import { AlertService } from '../../../services/alert.service';
import { ServiceBudgetService } from '../../../services/services-budget.service';
import { eventsService } from '../../../services/events.service';
@Component({
    selector: 'app-product-payment',
    templateUrl: './product-payment.component.html',
    styleUrls: ['./product-payment.component.css']
})
export class AppProductPaymentComponent implements OnInit {
    elements: Elements;
    cardNumber: StripeElement;
    cardExpiry: StripeElement;
    cardCvc: StripeElement;
    eventId: any;
    productId: any;
    MOBILE_NUMBER: any = /^[6789]\d{9}$/;
    countryData: any[] = [];
    elementsOptions: ElementsOptions = {
        locale: 'en'
    };

    stripeForm: FormGroup;
    isInit: boolean = false;
    planId: any;
    offerId: any
    paymentDetail: any;
    dialCode: any;
    mobileWithDialCode: any;
    mobileValid: boolean;
    constructor(private fb: FormBuilder, private route: ActivatedRoute, private router: Router, private alertService: AlertService,
        private stripeService: StripeService, private serviceBudgetService: ServiceBudgetService, private eventsService: eventsService) {
        this.route.paramMap.subscribe((param: ParamMap) => {
            this.eventId = param.get('id');
            this.productId = param.get('pid');
            this.getPaymentDetail();
        });
    }

    ngOnInit(): void {
        this.alertService.isShowHeader(true);
        this.alertService.isShowFooter(true);
        this.alertService.setHeaderClass('light');
        this.initComponent();
    }
    getNumber(event: any) {
        this.mobileWithDialCode = event;
    }
    onCountryChange(event: any) {
        this.dialCode = event.dialCode;
    }
    initComponent() {
        this.getCountry();
        this.stripeForm = this.fb.group({
            name: ['', [Validators.required]],
            street: ['', [Validators.required]],
            country: ['', [Validators.required]],
            quantity: ['', [Validators.required]],
            phoneNumber: ['', Validators.compose([
                Validators.required
            ])]
        });
        this.stripeService.elements(this.elementsOptions)
            .subscribe(elements => {
                this.elements = elements;
                // Only mount the element the first time
                if (!this.cardNumber) {
                    this.cardNumber = this.elements.create('cardNumber', {});
                    this.cardNumber.mount('#card-number');
                }
                if (!this.cardExpiry) {
                    this.cardExpiry = this.elements.create('cardExpiry', {});
                    this.cardExpiry.mount('#card-expiry');
                }
                if (!this.cardCvc) {
                    this.cardCvc = this.elements.create('cardCvc', {});
                    this.cardCvc.mount('#card-cvc');
                }
            });
        this.isInit = true;
    }
    getCountry() {
        this.serviceBudgetService.getCountry().subscribe((res: any) => {
            if (res.status) {
                this.countryData = res.data;
            }
        })
    }
    onError(obj) {
        this.mobileValid = obj;
    }
    makePayment() {
        const name = this.stripeForm.get('name').value;
        this.stripeService
            .createToken(this.cardNumber, { name })
            .subscribe(result => {
                if (result.token) {
                    // Use the token to create a charge or a customer
                    // https://stripe.com/docs/charges
                    this.addPayment(result.token.id);
                } else if (result.error) {
                    // Error creating the token
                    this.alertService.error(result.error.message);
                }
            });
    }
    addPayment(token: any) {
        let data: any = {
            productId: this.productId,
            eventId: this.eventId,
            paymentToken: token,
            quantity: this.stripeForm.get('quantity').value,
            street: this.stripeForm.get('street').value,
            country: this.stripeForm.get('country').value,
            phoneNumber: this.mobileWithDialCode
        }
        this.serviceBudgetService.productPayment(data).subscribe((res: any) => {
            if (res.status) {
                this.alertService.success(res.message, true);
                this.router.navigate(['/events']);
            }
            else {
                this.alertService.error(res.message)
            }
        })
    }
    getPaymentDetail() {
        this.eventsService.paymentDetail(this.productId).subscribe((res: any) => {
            if (res.status) {
                this.paymentDetail = res.data;
            }
        })
    }
}
