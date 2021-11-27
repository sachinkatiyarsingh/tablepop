import { Component, OnInit } from '@angular/core';
import { AlertService } from '../../services/alert.service';
import { AuthService } from '../../services/auth.service';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { environment } from '../../../environments/environment';
@Component({
  selector: 'app-footer',
  templateUrl: './footer.component.html',
  styleUrls: ['./footer.component.css']
})
export class FooterComponent implements OnInit {
  subscriptionForm: FormGroup;
  isUserLogin: boolean = false;
  calendly: string = environment.calendly;
  constructor(private alertService: AlertService, private authService: AuthService, private fb: FormBuilder) { }

  ngOnInit(): void {
    this.subscriptionForm = this.fb.group({
      email: ['', [Validators.email]]
    })
    this.authService.currentLoginStatus.subscribe((res) => {
      this.isUserLogin = res;
    })

  }
  goToTop() {
    this.alertService.gotoTop();
  }
  submit() {
    if (this.subscriptionForm) {
      const data = { ...this.subscriptionForm.value }
      this.authService.subscription(data).subscribe((res: any) => {
        if (res.status) {
          this.alertService.success(res.message);
          this.subscriptionForm.reset();
        } else {
          this.alertService.error(res.message);
        }
      })
    }
  }
}
