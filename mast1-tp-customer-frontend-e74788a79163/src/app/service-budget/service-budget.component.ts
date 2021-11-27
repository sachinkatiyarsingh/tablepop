/// <reference types="@types/googlemaps" />
import { Component, OnInit, NgZone } from '@angular/core';
import { ServiceBudgetService } from '../services/services-budget.service';
import { AlertService } from '../services/alert.service';
import { AuthService } from '../services/auth.service';
import { MapsAPILoader } from '@agm/core';
import { ActivatedRoute, ParamMap, Router } from '@angular/router';
import * as moment from 'moment';
@Component({
  selector: 'app-service-budget',
  templateUrl: './service-budget.component.html',
  styleUrls: ['./service-budget.component.css']
})
export class ServiceBudgetComponent implements OnInit {
  step: number = 1;
  id: any;
  currentDate: any;
  questiondata: any = {
    partyPlaningService: 'creative',
    partyPlaningServiceCatgeory: [],
    partyPlaningServiceSubCatgeory: []
  };
  currentUser: any;
  emailPattern: any = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/;
  mobilePattern: any = /^[6789]\d{9}$/;
  isOpenLogin: boolean = false;
  isOpenSignup: boolean = false;
  urlPattern: any = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/;

  datePickerConfig = {
    format: 'DD-MM-YYYY'
  };
  themes: any;
  dialCode: any;
  mobileWithDialCode: any;
  mobileValid: boolean;
  virtual: any;
  planner: any;
  isEventType: any;
  venue: any[] = [];
  serviceCategory: any;
  serviceSubCategory: any;
  selectedIndex: -1;
  constructor(private serviceBudgetService: ServiceBudgetService, private route: ActivatedRoute, private router: Router, private authService: AuthService, private alertService: AlertService, private mapsAPILoader: MapsAPILoader, private zone: NgZone) {
    this.route.paramMap.subscribe((param: ParamMap) => {
      this.id = param.get('id');
      if (this.id) {
        this.getDetails();
      }

    });
    this.route.queryParams.subscribe((params) => {
      if (Object.keys(params).length) {
        this.virtual = params.param;
      }
    });
    this.route.queryParams.subscribe((params) => {
      if (Object.keys(params).length) {
        this.planner = params.params;

      }
    });
  }

  ngOnInit(): void {
    this.alertService.isShowHeader(true);
    this.alertService.isShowFooter(false);
    this.alertService.setHeaderClass('light');
    this.isThemes();
    this.eventType();
    this.getVenue();
    this.getServiceCategory();
    this.authService.currentUserData.subscribe((res) => {
      this.currentUser = res;
      if (res && !this.isOpenLogin && !this.isOpenSignup) {
        this.questiondata.name = res.name + ' ' + res.surname;
        this.questiondata.email = res.email;
        this.questiondata.mobile = res.mobile;
        this.questiondata.eventName = res.eventName;
        this.validateEmail();
        this.validateMobile();
      }
      else if (res && (this.isOpenLogin || this.isOpenSignup)) {
        this.submit();
      }

    })
  }
  getNumber(event: any) {
    this.mobileWithDialCode = event;
  }
  onCountryChange(event: any) {
    this.dialCode = event.dialCode;
  }

  getDetails() {
    this.serviceBudgetService.questionnaireDetails(this.id).subscribe((res) => {

      if (res.status) {
        this.questiondata = res.data;
        this.questiondata.guestExpect = this.questiondata.guestExpectStart + '-' + this.questiondata.guestExpectEnd;
        if (this.questiondata.farEventDate) {
          this.questiondata.farEventDate = moment(this.questiondata.farEventDate);
        }
        this.validateEmail();
        this.validateMobile();
        this.validateUrl();
      }
    })
  }
  selectlevel(data: any) {
    if (this.questiondata.levelOfService != data) {
      this.questiondata.levelOfService = data;
      delete this.questiondata.levelOfServicePlanningType;
      delete this.virtual;
      delete this.planner;
    }
  }

  selectlevelPlanning(data: any) {
    this.questiondata.levelOfServicePlanningType = data;
  }

  selectvenue(data: any) {
    this.questiondata.vennuValue = data;
  }
  selectVannue(data: any) {
    this.questiondata.vennu = data;
    this.questiondata.vennuValue = '';
  }
  selectCategory(data: any, i: any) {
    this.selectedIndex = i;
    const indexOff = this.questiondata.partyPlaningServiceCatgeory.indexOf(data);
    this.questiondata.partyPlaningServiceCatgeory.push(data);
    this.serviceBudgetService.serviceSubCategory(data).subscribe((res: any) => {
      if (res.status) {
        this.serviceSubCategory = res.data;
      }
    })
  }
  selectSubCategory(data: any) {
    const idx = this.questiondata.partyPlaningServiceSubCatgeory.indexOf(data);
    if (idx >= 0) {
      this.questiondata.partyPlaningServiceSubCatgeory.splice(idx, 1);
    } else {
      this.questiondata.partyPlaningServiceSubCatgeory.push(data);
    }
  }
  selectTableTop(data: any) {
    this.questiondata.hearAbout = data;
  }

  selectPremiumEvent(data: any) {
    this.questiondata.premiumEvent = data;
  }
  selectEventPlanning(data: any) {
    if (this.questiondata.eventPlanning != data) {
      this.questiondata.eventPlanning = data;
      delete this.questiondata.typeEvent;
      delete this.questiondata.eventPlanningOther
    }
  }
  selectTypeEvent(data: any) {
    this.questiondata.typeEvent = data;
  }
  selectGuest(data: any) {
    this.questiondata.guestExpect = data;
  }
  selectFarEvent(data: any) {
    this.questiondata.farEvent = data;
  }
  selectFuture(data: any) {
    this.questiondata.farEventDate = data;
  }
  selectTheme(data: any) {
    this.questiondata.themeEvent = data.id;
  }
  fileChangeListener(files: any, fileInput: any) {
    if (!this.questiondata.files) {
      this.questiondata.files = [];
      this.questiondata.selectedFile = [];
    }
    for (let i = 0; i < files.length; i++) {
      this.questiondata.files.push(files[i]);
      const myReader: FileReader = new FileReader();
      myReader.onloadend = (e) => {
        this.questiondata.selectedFile.push(myReader.result);
      };
      myReader.readAsDataURL(files[i]);
    }
    fileInput.value = '';
  }
  removeImage(index: any) {
    this.questiondata.files.splice(index, 1);
    this.questiondata.selectedFile.splice(index, 1);
  }
  removeUImage(index: any, id: any) {
    this.serviceBudgetService.imageDelete(id, this.id).subscribe((res) => {
      if (res.status) {
        this.questiondata.addPhotos.splice(index, 1);
      }
    })
  }

  gotoStep(step: any) {
    this.step = step;
  }
  numberOnly(event): boolean {
    const charCode = (event.which) ? event.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
      return false;
    }
    return true;

  }
  validateEmail() {
    this.questiondata.validEmail = this.emailPattern.test(this.questiondata.email);
  }
  validateMobile() {
    if (this.questiondata.mobile != undefined && this.questiondata.mobile != null) {
      this.questiondata.validMobile = false;
    }
    this.questiondata.validMobile = this.mobilePattern.test(this.questiondata.mobile);
  }
  validateUrl() {
    this.questiondata.validUrl = this.urlPattern.test(this.questiondata.weddindIdeas);
  }
  onError(obj) {
    this.mobileValid = obj;
  }

  submit() {
    let formData: FormData = new FormData();
    if (this.currentUser) {
      formData.append("customerId", this.currentUser.id);
    }
    formData.append("levelOfService", this.questiondata.levelOfService || this.virtual || this.planner || '');
    formData.append("levelOfServicePlanningType", this.questiondata.levelOfServicePlanningType || '');
    formData.append("premiumEvent", this.questiondata.premiumEvent || '');
    formData.append("confirmationPartyPlanner", this.questiondata.confirmationPartyPlanner || '');
    formData.append("name", this.questiondata.name || '');
    formData.append("email", this.questiondata.email || '');
    formData.append("mobile", this.mobileWithDialCode || '');
    formData.append("eventName", this.questiondata.eventName || '');
    formData.append("typeEvent", this.questiondata.typeEvent || '');
    formData.append("eventPlanningOther", this.questiondata.eventPlanningOther || '');
    formData.append("eventPlanning", this.questiondata.eventPlanning || '');
    formData.append("partyPlaningServiceCatgeory", JSON.stringify(this.questiondata.partyPlaningServiceCatgeory) || '');
    formData.append("partyPlaningServiceSubCatgeory", JSON.stringify(this.questiondata.partyPlaningServiceSubCatgeory) || '');
    formData.append("guestExpect", this.questiondata.guestExpect || '');
    formData.append("farEvent", this.questiondata.farEvent || '');
    formData.append("partyPlaningService", this.questiondata.partyPlaningService || '');
    formData.append("vennu", this.questiondata.vennu || '');
    formData.append("vennuValue", this.questiondata.vennuValue || '');
    formData.append("themeEvent", this.questiondata.themeEvent || '');
    formData.append("latitude", this.questiondata.latitude || '');
    formData.append("longitude", this.questiondata.longitude || '');
    formData.append("hearAbout", this.questiondata.hearAbout || '');
    formData.append("weddindIdeas", this.questiondata.weddindIdeas || '');
    formData.append("anytningPartyPlanner", this.questiondata.anytningPartyPlanner || '');
    if (this.questiondata.files) {
      for (let i = 0; i < this.questiondata.files.length; i++) {
        formData.append("addPhotos[]", this.questiondata.files[i], this.questiondata.files[i].name);
      }
    }
    if (this.questiondata.hearAbout == 'other') {
      formData.append("hearAboutOther", this.questiondata.hearAboutOther || '');
    }
    formData.append("farEventDate", this.questiondata.farEventDate || '');

    if (this.questiondata.themeEvent == 'theme-other') {
      formData.append("themeEventOther", this.questiondata.themeEventOther || '');
    }
    if (this.id) {
      formData.append("questionnaireId", this.id);
      this.serviceBudgetService.questionnaireUpdate(formData).subscribe((res: any) => {
        if (res.status) {
          this.alertService.success(res.message);
          this.step = 12;
        }
        else {
          this.alertService.error(res.message);
        }
      })
    }
    else {
      this.serviceBudgetService.questionnaire(formData).subscribe((res: any) => {
        if (!res.status && !res.isCustomerExist && !this.currentUser) {
          this.isOpenSignup = true;
          this.authService.setShowSignup(true);
          // this.alertService.success(res.message);
        }
        else if (res.status && this.currentUser && res.isCustomerExist) {
          this.alertService.success(res.message);
          this.step = 12;
        }
        else if (!this.currentUser && res.isCustomerExist) {
          this.alertService.success(res.message);
          this.isOpenLogin = true;
          this.authService.setShowLogin(true);
        }
        else {
          this.alertService.error(res.message);
        }
      })
    }
  }
  onSearchChange(event: any, HTMLelement: HTMLInputElement): void {

    if (HTMLelement === undefined || HTMLelement === null) {
      return;
    }
    this.mapsAPILoader.load().then(() => {
      const autocomplete = new google.maps.places.Autocomplete(HTMLelement, {
        types: ['(cities)'],
      });
      autocomplete.addListener('place_changed', () => {
        this.zone.run(() => {
          const place: google.maps.places.PlaceResult = autocomplete.getPlace();
          if (place.geometry === undefined || place.geometry === null) {
            return;
          }
          this.questiondata.confirmationPartyPlanner = place.formatted_address;
          this.questiondata.latitude = place.geometry.location.lat();
          this.questiondata.longitude = place.geometry.location.lng();
        });
      });
    });
    event.preventDefault();
  }
  isThemes() {
    this.serviceBudgetService.themes().subscribe((res: any) => {
      if (res.status) {
        this.themes = res.data || {};
      }
    });
  }
  eventType() {
    this.serviceBudgetService.eventType().subscribe((res: any) => {
      if (res.status) {
        this.isEventType = res.data || {};
      }
    });
  }
  getVenue() {
    this.serviceBudgetService.venue().subscribe((res: any) => {
      if (res.status) {
        this.venue = res.data;
      }
    });
  }
  getServiceCategory() {
    this.serviceBudgetService.serviceCategory().subscribe((res: any) => {
      if (res.status) {
        this.serviceCategory = res.data;
      }
    })
  }

}
