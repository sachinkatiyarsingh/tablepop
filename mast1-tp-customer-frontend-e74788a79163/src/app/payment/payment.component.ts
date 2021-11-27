import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from "@angular/forms";
import { ActivatedRoute, ParamMap, Router } from '@angular/router';
import { StripeService, Elements, Element as StripeElement, ElementsOptions } from "ngx-stripe"
import { ServiceBudgetService } from '../services/services-budget.service';
import { AlertService } from '../services/alert.service';
import { MessageService } from '../services/message.service';
import { AuthService } from '../services/auth.service';
@Component({
    selector: 'app-payment',
    templateUrl: './payment.component.html',
    styleUrls: []
})
export class PaymentComponent implements OnInit {
    elements: Elements;
    cardNumber: StripeElement;
    cardExpiry: StripeElement;
    cardCvc: StripeElement;
    planId: any;
    questionareId: any;
    MOBILE_NUMBER: any = /^[6789]\d{9}$/;
    countryData: any[] = [];
    elementsOptions: ElementsOptions = {
        locale: 'en'
    };
    paymentDetail: any;
    offerId: any;
    productId: any;
    stripeForm: FormGroup;
    userAddress: any[] = [];
    dialCode: any;
    mobileWithDialCode: any;
    mobileValid: boolean;
    pressed: boolean = false;
    constructor(private fb: FormBuilder, private route: ActivatedRoute, private router: Router, private authService: AuthService, private messageService: MessageService, private alertService: AlertService,
        private stripeService: StripeService, private serviceBudgetService: ServiceBudgetService) {
        this.route.paramMap.subscribe((param: ParamMap) => {
            this.planId = param.get('planId');
            this.questionareId = param.get('qid');
        });
    }

    ngOnInit(): void {
        this.alertService.isShowHeader(true);
        this.alertService.isShowFooter(true);
        this.alertService.setHeaderClass('light');
        this.getCountry();
        this.getPaymentDetail();
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
    getCountry() {
        this.serviceBudgetService.getCountry().subscribe((res: any) => {
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
                    // Error creating the token
                    this.alertService.error(result.error.message);
                }
            });
    }
    addPayment(token: any) {
        let data: any = {
            questionnaireId: this.questionareId,
            planId: this.planId,
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
        this.pressed = false;
        this.serviceBudgetService.customerPayment(data).subscribe((res: any) => {
            this.pressed = false;
            if (res.status) {
                this.alertService.success(res.message);
                if (res.data) {
                    this.messageService.initSocket();
                    setTimeout(() => {
                        this.messageService.sendEvent('new_group', { groupId: res.data.groupId });
                        this.router.navigate(['/message']);
                    })
                }
                else {
                    this.router.navigate(['/message']);
                }
            }
            else {
                this.alertService.error(res.message)
            }
        })
    }
    getPaymentDetail() {
        this.serviceBudgetService.paymentDetail(this.planId).subscribe((res: any) => {
            if (res.status) {
                this.paymentDetail = res.data;
            }
        })
    }
}
