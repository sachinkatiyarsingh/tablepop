import { Component, OnInit } from '@angular/core';
import { ContactUsService } from '../services/contactus.service';
import { AlertService } from '../services/alert.service';
import { FormBuilder, FormGroup, Validator, Validators } from '@angular/forms';
import { environment } from '../../environments/environment';
@Component({
  selector: 'app-contactus',
  templateUrl: './contactus.component.html',
  styleUrls: ['./contactus.component.css']
})
export class ContactusComponent implements OnInit {
  contactForm: FormGroup;
  calendly: string = environment.calendly;
  constructor(private fb: FormBuilder, private contactUs: ContactUsService, private alertService: AlertService) { }

  ngOnInit(): void {
    this.alertService.isShowHeader(true);
    this.alertService.isShowFooter(true);
    this.alertService.setHeaderClass('');
    this.initContactForm();
  }

  initContactForm() {
    this.contactForm = this.fb.group({
      name: ['', [Validators.required]],
      email: ['', [Validators.required, Validators.email]],
      message: ['', [Validators.required]],
    })
  }

  submit() {
    if (this.contactForm) {
      const formData = { ...this.contactForm.value };
      this.contactUs.contactUs(formData).subscribe((res: any) => {
        if (res.status) {
          this.alertService.success(res.message);
          this.contactForm.reset();
        }
        else {
          this.alertService.error(res.message);
        }
      })
    }
  }
}
