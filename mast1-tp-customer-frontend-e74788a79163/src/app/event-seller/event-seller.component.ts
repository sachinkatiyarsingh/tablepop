import { Component, OnInit, Output, EventEmitter } from '@angular/core';
import { eventsService } from '../services/events.service';
import { AlertService } from '../services/alert.service';
import { Router, ActivatedRoute, ParamMap } from "@angular/router"
@Component({
  selector: 'app-event-seller',
  templateUrl: './event-seller.component.html',
  styleUrls: ['./event-seller.component.css']
})
export class EventSellerComponent implements OnInit {
  eventId: any;
  eventSeller: any[] = [];
  totalPage: any;
  pageOpts = {
    PageNumber: 1,
    PageSize: 10
  };
  sellerId: any;
  favorite: boolean = false;
  markfav: boolean;
  constructor(private eventService: eventsService, private alertService: AlertService, private router: Router, private route: ActivatedRoute,) {
    this.route.paramMap.subscribe((param: ParamMap) => {
      this.eventId = param.get('id');
      this.getEventSeller();
    });
  }

  ngOnInit(): void {
    this.alertService.isShowHeader(true);
    this.alertService.isShowFooter(true);
    this.alertService.setHeaderClass('light');
  }
  getEventSeller() {
    this.eventService.eventSeller(this.eventId).subscribe((res: any) => {
      if (res.status) {
        this.eventSeller = res.data;
        this.eventSeller.forEach(element => {
          var rating = [{ index: 1, active: false }, { index: 2, active: false }, { index: 3, active: false }, { index: 4, active: false }, { index: 5, active: false }];
          for (var r = 1; r <= element.rating; r++) {
            rating[r - 1].active = true;
          }
          element.reviewRating = rating;
        });
      }
    });
  }
  goToDetail(data: any) {
    if (data.type == 'Vendor') {
      this.router.navigate(['/vendor', data.id]);
    }
    else if (data.type == 'Planner') {
      this.router.navigate(['/planners', data.id]);
    }
  }
  markFavorite(data) {
    if (data.favorite == 1)
      return;
    data.favorite = (data.favorite == "" || data.favorite == null || data.favorite == undefined || data.favorite == 0) ? 1 : 0;
    this.eventService.markFavorite(data.id).subscribe((res: any) => {
      if (res.status) {
        //this.favorite = true;
        this.alertService.success(res.message, true);
      } else {
        this.alertService.error(res.message);
        // this.favorite = false;
      };
    })
  }

}
