import { Component, OnInit } from '@angular/core';

import { eventsService } from '../services/events.service';
import { AlertService } from '../services/alert.service';
import { AuthService } from '../services/auth.service';
@Component({
  selector: 'app-events',
  templateUrl: './events.component.html',
  styleUrls: ['./events.component.css']
})
export class EventsComponent implements OnInit {
  questionnaireList: any[] = [];
  pageOpts = {
    PageNumber: 1,
    PageSize: 10
  };
  sortType: any = 'recent';
  length: number = 0;
  type: any = "";
  noRecordFound: boolean = false;
  constructor(private eventsServices: eventsService, private alertService: AlertService) { }

  ngOnInit(): void {
    this.alertService.isShowHeader(true);
    this.alertService.isShowFooter(false);
    this.alertService.setHeaderClass('light');
    this.showQuestionnaireList()
  }

  showQuestionnaireList() {
    var res = {
      PageNumber: this.pageOpts.PageNumber,
      PageSize: this.pageOpts.PageSize,
      sort_by: this.sortType,
      type: this.type == '' ? '' : this.type
    }
    this.alertService.showLoader(true)
    this.eventsServices.questionnaire_list(res).subscribe((req: any) => {
      if (req.status) {
        this.questionnaireList = req.data.eventList;
        this.noRecordFound = this.questionnaireList.length != undefined && this.questionnaireList.length != null && this.questionnaireList.length > 0 ? false : true;
        this.length = req.data.totalPage;
        this.alertService.showLoader(false)
      } else {
        this.alertService.showLoader(false)
      }
    }),
      (error: any) => {
        this.alertService.showLoader(false);
        this.alertService.error(error.message);
      }
  }
  getEvent(type: any) {
    if (type == this.type) {
      return;
    }
    this.type = type;
    this.pageOpts.PageNumber = 1;
    this.showQuestionnaireList();

  }
  PageSelect(PageNumber) {
    this.pageOpts.PageNumber = PageNumber;
    this.showQuestionnaireList();
  }
  sortBy(type: any) {
    this.sortType = type;
    this.pageOpts.PageNumber = 1;
    this.showQuestionnaireList();
  }
}
