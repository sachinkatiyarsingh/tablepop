import { Component, OnInit } from '@angular/core';
import { AlertService } from '../services/alert.service';

@Component({
  selector: 'app-howsit-works2',
  templateUrl: './howsit-works2.component.html',
  styleUrls: ['./howsit-works2.component.css']
})
export class HowsitWorks2Component implements OnInit {

  constructor(private alertService: AlertService) { }

  ngOnInit(): void {
    this.alertService.isShowHeader(true);
    this.alertService.isShowFooter(true);
    this.alertService.setHeaderClass('light theme');
  }

}
