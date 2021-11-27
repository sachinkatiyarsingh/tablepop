import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { environment } from '../../environments/environment';
import { AlertService } from '../services/alert.service';
@Component({
  selector: 'app-find-a-planner',
  templateUrl: './find-a-planner.component.html',
  styleUrls: ['./find-a-planner.component.css']
})
export class FindAPlannerComponent implements OnInit {
  isCalendly: boolean = false;
  calendly: string = environment.calendly;
  constructor(private router: Router, private alertService: AlertService) { }

  ngOnInit(): void {
    this.alertService.isShowHeader(true);
    this.alertService.isShowFooter(true);
    this.alertService.setHeaderClass('light theme');
    // Calendly.initInlineWidget({
    //   url: 'https://calendly.com/test-cei',
    //   parentElement: document.querySelector('.calendly-inline-widget'),
    // });
  }

  goToService() {
    this.router.navigate(['servicebudget'], { queryParams: { param: 'virtual' } });
  }
  onlinePlanner() {
    this.router.navigate(['servicebudget'], { queryParams: { params: 'planner' } });
  }
}
