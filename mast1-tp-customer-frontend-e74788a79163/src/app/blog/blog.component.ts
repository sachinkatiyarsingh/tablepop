import { Component, OnInit } from '@angular/core';
import { BlogService } from '../services/blog.service';
import { AlertService } from '../services/alert.service';
import { ActivatedRoute, Router } from "@angular/router";
@Component({
  selector: 'app-blog',
  templateUrl: './blog.component.html',
  styleUrls: ['./blog.component.css']
})
export class BlogComponent implements OnInit {
  blogData: any;
  totalPage: any;
  pageOpts = {
    PageNumber: 1,
    PageSize: 10
  };
  constructor(private alertService: AlertService, private blogService: BlogService, private route: Router) { }

  ngOnInit(): void {
    this.alertService.isShowHeader(true);
    this.alertService.isShowFooter(true);
    this.alertService.setHeaderClass('light');
    this.getBlogData();
  }
  getBlogData() {
    var req = {
      pageNo: this.pageOpts.PageNumber,
      PageSize: this.pageOpts.PageSize,
    }
    this.blogService.blog(req).subscribe((res: any) => {
      if (res.status) {
        this.blogData = res.data;
        this.totalPage = res.data ? res.data.totalPage : 0;
      }
    })
  }
  goToDetail(id: any) {
    this.route.navigate(['/blog/detail', id])
  }
  PageSelect(PageNumber: any) {
    this.pageOpts.PageNumber = PageNumber;
    this.getBlogData();
  }
}
