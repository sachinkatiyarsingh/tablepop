import { Component, OnInit } from '@angular/core';
import { AlertService } from "../services/alert.service";
import { AuthService } from '../services/auth.service';
import { FormBuilder, FormGroup, Validators } from "@angular/forms";
import * as moment from 'moment';
@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.css']
})
export class ProfileComponent implements OnInit {
  profileForm: FormGroup;
  countryData: any[] = [];
  stateData: any[] = [];
  profileData: any;
  currentUser: any;
  password: boolean = false;
  userAddress: any[] = [];
  isAddressAddEdit: boolean = false;
  addressForm: FormGroup;
  isNewAddress: boolean = false;
  selectedIndex: any = -1;
  dialCode: any;
  dialCodes: any;
  mobileWithDialCode: any;
  mobileWithTel: any;
  mobileValid: boolean;
  mobileValidation: boolean;
  addressSection: boolean = false
  constructor(private alertService: AlertService, private authService: AuthService, private fb: FormBuilder) { }

  ngOnInit(): void {
    this.alertService.isShowHeader(true);
    this.alertService.isShowFooter(false);
    this.alertService.setHeaderClass('light');
    this.getCountry()
    this.address();
    this.authService.currentUserData.subscribe((res) => {
      this.profileData = res;
    })
    this.initializeForm();
  }
  initializeForm() {
    if (this.profileData.country_id) {
      this.getState(this.profileData.country_id)
    }
    this.profileForm = this.fb.group({
      name: [this.profileData.name, [Validators.required]],
      surname: [this.profileData.surname,],
      mobile: [this.profileData.mobile,],
      country: [this.profileData.country_id,],
      state: [this.profileData.state_id,],
      currentPassword: [{ value: '', disabled: true }],
      newPassword: [],
      confirmPassword: [],
    });
    console.log(this.profileForm.controls.mobile.value);
  }
  getCountry() {
    this.authService.getCountry().subscribe((res: any) => {
      if (res.status) {
        this.countryData = res.data;
      }
    })
  }
  getState(countryId: any) {
    this.authService.getState(countryId).subscribe((res: any) => {
      if (res.status) {
        this.stateData = res.data;
        //this.profileForm.get('state').enable();
      }
    })
  }
  changeCountry(e: any) {
    this.profileForm.get('state').setValue("");
    //this.profileForm.get('state').disable();
    this.stateData = [];
    this.getState(e.target.value);
  }
  getNumber(event: any) {
    this.mobileWithDialCode = event;
  }
  getNumberTel(event: any) {
    this.mobileWithTel = event;
  }
  onCountryChange(event: any) {
    this.dialCode = event.dialCode;
  }
  telCountryChange(event: any) {
    this.dialCodes = event.dialCode;
  }
  onError(obj) {
    this.mobileValid = obj;
  }
  onErrors(obj) {
    this.mobileValidation = obj;
  }
  profile_Update() {
    const data: any = { ...this.profileForm.getRawValue() };
    let _form: FormData = new FormData();
    _form.append('name', data.name);
    _form.append('surname', data.surname || '');
    _form.append('mobile', this.mobileWithDialCode || '');
    _form.append('country', data.country || '');
    _form.append('state', data.state || '')
    if (this.profileData.files) {
      _form.append('image', this.profileData.files, this.profileData.files.name);

    }
    this.authService.profile(_form).subscribe((res: any) => {
      if (res.status) {
        this.profileData = res.data;
        this.authService.updateUserDetail(res.data);
        this.alertService.success('Profile Update successful.');
      }
      else {
        this.alertService.error(res.message);
      }
    })
  }
  fileChangeListener(files: any, fileInput: any) {
    this.profileData.files = files[0];
    const myReader: FileReader = new FileReader();
    myReader.onloadend = (e) => {
      this.profileData.image = myReader.result;
    };
    myReader.readAsDataURL(files[0]);
    fileInput.value = '';
    let _form: FormData = new FormData();
    if (this.profileData.files) {
      _form.append('image', this.profileData.files, this.profileData.files.name);

    }
    this.authService.profile(_form).subscribe((res: any) => {
      if (res.status) {
        this.profileData = res.data;
        this.authService.updateUserDetail(res.data);
        this.alertService.success('Profile Photo Update successfull.');
      }
      else {
        this.alertService.error(res.message);
      }
    })
  }
  enableContactInfo() {
    this.password = !this.password
    // this.profileForm.controls['name'].enable();
    // this.profileForm.controls['surname'].enable();
    // this.profileForm.controls['country'].enable();
    // this.profileForm.controls['state'].enable();
    // this.profileForm.controls['mobile'].enable();
  }
  enableDisableControle(controle: any, ele?: any) {
    if (this.profileForm.controls[controle].disabled) {
      this.profileForm.controls[controle].enable();
      if (ele) {
        // ele.focus();
      }
    }
    else {
      this.profileForm.controls[controle].disable();
    }
  }
  updatePassword() {
    if (!this.profileForm.get('currentPassword').value && (this.profileForm.get('newPassword').value !== this.profileForm.get('confirmPassword').value)) {
      return;
    }
    const data: any = {
      currentPassword: this.profileForm.get('currentPassword').value,
      newPassword: this.profileForm.get('newPassword').value,
      confirmPassword: this.profileForm.get('confirmPassword').value
    }
    this.alertService.showLoader;
    this.authService.customerChangePassword(data).subscribe(
      (res: any) => {
        if (res.status) {
          this.profileForm.controls['currentPassword'].disable();
          this.alertService.success(res.message);
        } else {
          this.alertService.error(res.message);
        }
      },
      (error: any) => {
        this.alertService.error(error);
      }
    )
  }
  addNewAddress(data: any, index: any) {
    this.selectedIndex = index;
    this.isNewAddress = !data.id;
    this.addressForm = this.fb.group({
      addressId: [data.id],
      street: [data.street || '', [Validators.required]],
      country: [data.country || '', [Validators.required]],
      phoneNumber: [data.phoneNumber, Validators.compose([
        Validators.required
      ])]
    });
    this.isAddressAddEdit = true;
  }
  address() {
    this.authService.address().subscribe((res: any) => {
      if (res.status) {
        this.userAddress = res.data ? res.data.address || [] : [];
      }
    })
  }
  addAddress() {
    let _data: any = { ...this.addressForm.value };
    _data.phoneNumber = this.mobileWithTel;
    delete _data.addressId;
    this.authService.addAddress(_data).subscribe((res: any) => {
      if (res.status) {
        this.userAddress.push(res.data);
        this.isAddressAddEdit = false;
      }
    })
  }
  editAddress() {
    let _data: any = { ...this.addressForm.value };
    _data.phoneNumber = this.mobileWithTel;
    this.authService.editAddress(_data).subscribe((res: any) => {
      if (res.status) {
        this.userAddress[this.selectedIndex] = res.data;
        this.isAddressAddEdit = false;
      }
    })
  }
  deleteAddress(id: any, index) {
    this.authService.deleteAddress(id).subscribe((res: any) => {
      if (res.status) {
        this.userAddress.splice(index, 1);
        this.isAddressAddEdit = false;
      }
    })
  }
}
