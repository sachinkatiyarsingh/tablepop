import { Component, OnInit } from '@angular/core';
import { eventsService } from '../services/events.service';
import { Router, ActivatedRoute, ParamMap } from "@angular/router";
import { AlertService } from '../services/alert.service';
@Component({
  selector: 'app-milestone-view',
  templateUrl: './milestone-view.component.html',
  styleUrls: ['./milestone-view.component.css']
})
export class MilestoneViewComponent implements OnInit {
  milestones: any[] = [];
  questionnaireId: any;
  constructor(private eventService: eventsService, private router: Router, private route: ActivatedRoute, private alertService: AlertService) {
    this.route.paramMap.subscribe((param: ParamMap) => {
      this.questionnaireId = param.get('id');
    });
  }

  ngOnInit(): void {
    this.alertService.isShowHeader(true);
    this.alertService.isShowFooter(false);
    this.alertService.setHeaderClass('');
    this.viewMilestones()
  }
  viewMilestones() {
    this.eventService.viewMilestones(this.questionnaireId).subscribe((res: any) => {
      if (res.status) {
        if (res.message == "Data Empty") {
          this.router.navigate(['/events']);
          this.alertService.error("No Data Found");
        } else {
          this.milestones = res.data || {};
        }
      }
    })
  }
  changestatus(data: any) {
    this.eventService.changeStatus(2, data.id).subscribe((res) => {
      if (res.status) {
        this.alertService.success(res.message, true);
        data.status = 2;
      }
      else {
        this.alertService.error(res.message);
      }
    })
  }
}
