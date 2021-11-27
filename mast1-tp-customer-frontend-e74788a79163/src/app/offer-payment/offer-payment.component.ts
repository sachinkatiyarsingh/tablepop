import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from "@angular/forms";
import { ActivatedRoute, ParamMap, Router } from '@angular/router';
import { StripeService, Elements, Element as StripeElement, ElementsOptions } from "ngx-stripe"
import { customerOfferService } from '../services/customer-offer.service';
import { AlertService } from '../services/alert.service';
import { eventsService } from '../services/events.service';
import { AuthService } from '../services/auth.service';
@Component({
    selector: 'app-offer-payment',
    templateUrl: './offer-payment.component.html',
    styleUrls: ['./offer-payment.component.css']
})
export class OfferPaymentComponent implements OnInit {
    elements: Elements;
    cardNumber: StripeElement;
    cardExpiry: StripeElement;
    cardCvc: StripeElement;
    offerId: any;
    questionareId: any;
    MOBILE_NUMBER: any = /^[6789]\d{9}$/;
    countryData: any[] = [];
    elementsOptions: ElementsOptions = {
        locale: 'en'
    };

    stripeForm: FormGroup;
    isInit: boolean = false;
    paymentDetail: any;
    userAddress: any[] = [];
    dialCode: any;
    mobileWithDialCode: any;
    mobileValid: boolean;
    pressed: boolean = false;
    constructor(private fb: FormBuilder, private route: ActivatedRoute, private router: Router, private authService: AuthService, private alertService: AlertService,
        private stripeService: StripeService, private customerOffers: customerOfferService, private eventService: eventsService) {
        this.route.paramMap.subscribe((param: ParamMap) => {
            this.offerId = param.get('offerId');
            this.getOfferDetail();
        });
    }

    ngOnInit(): void {
        this.alertService.isShowHeader(true);
        this.alertService.isShowFooter(true);
        this.alertService.setHeaderClass('light');
        this.getPaymentDetail();
    }
    getNumber(event: any) {
        this.mobileWithDialCode = event;
    }
    onCountryChange(event: any) {
        this.dialCode = event.dialCode;
    }
    onError(obj) {
        this.mobileValid = obj;
    }
    initComponent() {
        this.getCountry();
        this.address();
        this.stripeForm = this.fb.group({
            name: ['', [Validators.required]],
            street: ['', [Validators.required]],
            country: ['', [Validators.required]],
            addressId: [],
            phoneNumber: ['', Validators.compose([
                Validators.required
            ])]
        });
        this.isInit = true;
        setTimeout(() => {
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
        }, 1000)
    }
    getOfferDetail() {
        this.customerOffers.getOfferDetail(this.offerId).subscribe((res: any) => {
            if (res.status) {
                if (res.data && res.data.status == 1) {
                    this.router.navigate(['/events']);
                }
                else {
                    this.initComponent();
                }
            }
        })
    }
    getCountry() {
        this.customerOffers.getCountry().subscribe((res: any) => {
            if (res.status) {
                this.countryData = res.data;
            }
        })
    }
    address() {
        this.authService.address().subscribe((res: any) => {
            if (res.status) {
                this.userAddress = res.data ? res.data.address || [] : [];
            }
        })
    }
    onChangeAddress(event: any) {
        this.stripeForm.controls['street'].setValidators(null);
        this.stripeForm.controls['street'].setValue(null);
        this.stripeForm.controls['country'].setValidators(null);
        this.stripeForm.controls['country'].setValue(null);
        this.stripeForm.controls['phoneNumber'].setValidators(null);
        this.stripeForm.controls['phoneNumber'].setValue(null);
    }
    makePayment() {
        const name = this.stripeForm.get('name').value;
        this.stripeService
            .createToken(this.cardNumber, { name })
            .subscribe(result => {
                if (result.token) {
                    this.addPayment(result.token.id);
                } else if (result.error) {
                    this.alertService.error(result.error.message);
                }
            });
    }
    addPayment(token: any) {
        let data: any = {
            offerId: this.offerId,
            paymentToken: token
        }
        if (this.stripeForm.get('addressId').value) {
            data.addressId = this.stripeForm.get('addressId').value
        }
        else {
            data.street = this.stripeForm.get('street').value;
            data.country = this.stripeForm.get('country').value;
            data.phoneNumber = this.mobileWithDialCode;
        }
        this.customerOffers.customerOfferPayment(data).subscribe((res: any) => {
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
        this.customerOffers.paymentDetail(this.offerId).subscribe((res: any) => {
            if (res.status) {
                this.paymentDetail = res.data;
            }
        })
    }
}
