import { Component, OnInit } from '@angular/core';
import { FaqService } from '../services/faq.service';
import { AlertService } from '../services/alert.service';
@Component({
  selector: 'app-faq',
  templateUrl: './faq.component.html',
  styleUrls: ['./faq.component.css']
})
export class FaqComponent implements OnInit {
  step: any;
  pageNo: any;
  faqData: any[] = [];
  visibleIndex = -1;
  constructor(private faqservice: FaqService, private alertService: AlertService) { }

  ngOnInit(): void {
    this.getFaq();
  }

  getFaq() {
    this.alertService.isShowHeader(true);
    this.alertService.isShowFooter(true);
    this.alertService.setHeaderClass('light theme');
    this.faqservice.faq(this.pageNo).subscribe((res: any) => {
      if (res.status) {
        this.faqData = res.data;
      }
    })
  }
  steps(ind: any) {
    if (this.visibleIndex === ind) {
      this.visibleIndex = -1;
    } else {
      this.visibleIndex = ind;
    }
  }
}
