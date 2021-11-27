import { Component, OnInit } from '@angular/core';
import { eventsService } from '../services/events.service';
import { AlertService } from '../services/alert.service';
import { Router, ActivatedRoute, ParamMap } from "@angular/router";
@Component({
  selector: 'app-favorite-seller',
  templateUrl: './favorite-seller.component.html',
  styleUrls: ['./favorite-seller.component.css']
})
export class FavoriteSellerComponent implements OnInit {
  favoriteSeller: any[] = [];
  noRecordFound: boolean = true;
  constructor(private eventService: eventsService, private alertService: AlertService, private router: Router, private route: ActivatedRoute,) { }

  ngOnInit(): void {
    this.alertService.isShowHeader(true);
    this.alertService.isShowFooter(true);
    this.alertService.setHeaderClass('light');
    this.getFavoriteSeller();
  }
  getFavoriteSeller() {
    this.eventService.favoriteSellers().subscribe((res: any) => {
      if (res.status) {
        this.favoriteSeller = res.data;
        this.noRecordFound = this.favoriteSeller?.length != undefined && this.favoriteSeller.length != null && this.favoriteSeller.length > 0 ? false : true;
        if (this.favoriteSeller) {
          this.favoriteSeller.forEach(element => {
            var rating = [{ index: 1, active: false }, { index: 2, active: false }, { index: 3, active: false }, { index: 4, active: false }, { index: 5, active: false }];
            for (var r = 1; r <= element.rating; r++) {
              rating[r - 1].active = true;
            }
            element.reviewRating = rating;
          });
        };
      }
    })
  }
  unmarkFavorite(data: any) {
    data.favorite = (data.favorite == 1 || data.favorite == null || data.favorite == undefined) ? "" : 1;
    this.eventService.unmarkFavorite(data.id).subscribe((res: any) => {
      if (res.status) {
        this.alertService.success(res.message);
        this.getFavoriteSeller();
      } else {
        this.alertService.error(res.message);
      }
    })
  }
  goToDetail(data: any) {
    if (data.type == 'Vendor') {
      this.router.navigate(['/vendor', data.id]);
    }
    else if (data.type == 'Planner') {
      this.router.navigate(['/planners', data.id]);
    }
  }
}
