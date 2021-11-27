import { Component, OnInit } from '@angular/core';
import { environment } from "../../environments/environment";
import { AlertService } from '../services/alert.service';
@Component({
  selector: 'app-become-aplanner',
  templateUrl: './become-aplanner.component.html',
  styleUrls: ['./become-aplanner.component.css']
})
export class BecomeAPlannerComponent implements OnInit {
  plannerUrl: any = environment.plannerUrl;
  vendorUrl: any = environment.vendorUrl;
  constructor(private alertService: AlertService) { }

  ngOnInit(): void {
    this.alertService.isShowHeader(true);
    this.alertService.isShowFooter(true);
    this.alertService.setHeaderClass('light');
  }
}
