import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, ParamMap, Router } from '@angular/router';
import { AuthService } from '../services/auth.service';
import { AlertService } from '../services/alert.service';
import { ConfirmPasswordValidator } from '../custom-validators/confirm-password.validator';
@Component({
    selector: 'app-reset-password',
    templateUrl: './password-reset.component.html',
    styleUrls: []
})
export class ResetPasswordComponent implements OnInit {
    resetForm: FormGroup;
    token: any;
    constructor(private fb: FormBuilder, private route: ActivatedRoute, private router: Router, private alertService: AlertService, private authService: AuthService) {
        this.route.paramMap.subscribe((param: ParamMap) => {
            this.token = param.get('token');
        });
    }

    ngOnInit(): void {
        this.alertService.isShowHeader(false);
        this.alertService.isShowFooter(false);
        this.alertService.setHeaderClass('light');
        this.resetForm = this.fb.group({
            password: ['', [Validators.required]],
            confirmPassword: []
        }, { validator: ConfirmPasswordValidator.MatchPassword });
    }
    submit() {
        if (this.resetForm.valid) {
            const data: any = { ...this.resetForm.value };
            this.authService.resetPassword(this.token, { password: data.password, c_password: data.confirmPassword }).subscribe((res: any) => {
                if (res.status) {
                    this.alertService.success(res.message);
                    this.authService.setShowLogin(true);
                    this.router.navigate(['/']);
                }
                else {
                    this.alertService.error(res.message);
                }
            })
        }
    }

}
