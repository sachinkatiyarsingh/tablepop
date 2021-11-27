import { Component, OnInit } from '@angular/core';
import { AlertService } from '../services/alert.service';

@Component({
  selector: 'app-howsit-works',
  templateUrl: './howsit-works.component.html',
  styleUrls: ['./howsit-works.component.css']
})
export class HowsitWorksComponent implements OnInit {

  constructor(private alertService: AlertService) { }

  ngOnInit(): void {
    this.alertService.isShowHeader(true);
    this.alertService.isShowFooter(true);
    this.alertService.setHeaderClass('light');
  }

}
