import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, ParamMap, Router } from '@angular/router';
import { BlogService } from "../../services/blog.service";
import { AlertService } from '../../services/alert.service';
import { OwlOptions } from 'ngx-owl-carousel-o';
@Component({
  selector: 'app-blog-detail',
  templateUrl: './blog-detail.component.html',
  styleUrls: ['./blog-detail.component.css']
})
export class BlogDetailComponent implements OnInit {
  blogs: any;
  blogId: any;
  customOptions: OwlOptions = {
    loop: false,
    items: 1,
    margin: 10,
    nav: false,
    dots: false,
    navText: ['', '']
  };
  constructor(private blogService: BlogService, private alertService: AlertService, private route: ActivatedRoute, private router: Router,) {
    this.route.paramMap.subscribe((param: ParamMap) => {
      this.blogId = param.get('id');
      this.getBlog();
    });
  }

  ngOnInit(): void {
    this.alertService.isShowHeader(true);
    this.alertService.isShowFooter(true);
    this.alertService.setHeaderClass('light');
  }
  getBlog() {
    this.blogService.blogDetail(this.blogId).subscribe((res: any) => {
      if (res.status) {
        this.blogs = res.data;
      }
    })
  }
}
