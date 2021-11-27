import { AfterViewChecked, ElementRef, ViewChild, Component, OnInit, HostListener, ChangeDetectorRef } from '@angular/core';
import { OwlOptions } from 'ngx-owl-carousel-o';
import simpleParallax from 'simple-parallax-js';
import { LazyLoadScriptService } from '../services/lazy-load-script.service';
import { map, filter, take, switchMap } from 'rxjs/operators';
import { ActivatedRoute, Router } from '@angular/router'
import { AlertService } from '../services/alert.service';
declare var $;
declare var ScrollOut;
declare var Rellax: any;
@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css'],
  providers: [LazyLoadScriptService]
})
export class HomeComponent implements OnInit, AfterViewChecked {
  // @ViewChild('step1') private step1: ElementRef;
  // @ViewChild('step2') private step2: ElementRef;
  // @ViewChild('step3') private step3: ElementRef;
  //isZoom: boolean = false;

  pos: number = 0;
  customOptions: OwlOptions = {
    loop: false,
    items: 1,
    margin: 10,
    nav: false,
    dots: false,
    navText: ['', '']
  };
  config: any = {
    axis: 'Y',
    speed: -.5
  }
  slideConfig = { "slidesToShow": 4, "slidesToScroll": 4 };
  constructor(private lazyLoadScriptService: LazyLoadScriptService, private router: Router, private alertService: AlertService) { }

  ngOnInit(): void {
    this.alertService.isShowHeader(true);
    this.alertService.isShowFooter(true);
    this.alertService.setHeaderClass('');
    let images: any = document.querySelectorAll('.rellax');
    new simpleParallax(images);
  }
  goToService() {
    this.router.navigate(['servicebudget'], { queryParams: { param: 'in-person' } });
  }
  onlinePlanner() {
    this.router.navigate(['servicebudget'], { queryParams: { params: 'online' } });
  }
  ngAfterViewInit() {
    this.lazyLoadScriptService.loadScript('https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js')
      .pipe(
        map(_ => 'jQuery is loaded'),
        filter(jquery => !!jquery),
        take(1),
        switchMap(_ => this.lazyLoadScriptService.loadScript('https://unpkg.com/scroll-out/dist/scroll-out.min.js')),
        switchMap(_ => this.lazyLoadScriptService.loadScript('https://kenwheeler.github.io/slick/slick/slick.js')),

      )
      .subscribe(_ => {
        $(".slider-for").slick({
          dots: false,
          centerMode: true,
          slidesToShow: 1,
          arrows: false,
          centerPadding: '0',
          autoplay: true,
          autoplaySpeed: 6000,
          asNavFor: '.slider-nav'
        });

        $(".slider-nav").slick({
          dots: false,
          centerMode: true,
          arrows: false,
          slidesToShow: 1,
          centerPadding: '0',
          autoplay: true,
          autoplaySpeed: 6000,
          asNavFor: '.slider-for'
        });
        ScrollOut({
          offsetY: 400
        });

        var rellax = new Rellax('.rellax', {
          speed: 2,
          center: true,
          wrapper: null,
          round: true,
          vertical: true,
          horizontal: false,
        });
      });
  }
  ngAfterViewChecked() {
    // this.scrollWidth()
    setTimeout(() => {
      this.scrollToBottom();
    }, 1000);

  }

  // @HostListener('window:scroll', [])
  // onWindowScroll() {
  //   const window_top_position = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
  //   const window_height: number = window.innerHeight;
  //   const window_bottom_position = (window_top_position + window_height);
  //   const element_height = this.step1.nativeElement.scrollHeight;
  //   const element_top_position = this.step1.nativeElement.offsetTop;
  //   const element_bottom_position = (element_top_position + element_height);
  //   const element_height2 = this.step2.nativeElement.scrollHeight;
  //   const element_top_position2 = this.step2.nativeElement.offsetTop;
  //   const element_bottom_position2 = (element_top_position + element_height);
  //   const element_height3 = this.step3.nativeElement.scrollHeight;
  //   const element_top_position3 = this.step3.nativeElement.offsetTop;
  //   const element_bottom_position3 = (element_top_position + element_height);
  //   if ((element_bottom_position >= window_top_position) &&
  //     (element_top_position <= window_bottom_position)) {
  //     this.step1.nativeElement.classList.add('in-view');
  //   } else {
  //     this.step1.nativeElement.classList.remove('in-view');
  //   }
  //   if ((element_bottom_position2 >= window_top_position) &&
  //     (element_top_position2 <= window_bottom_position)) {
  //     this.step2.nativeElement.classList.add('in-view');
  //   } else {
  //     this.step2.nativeElement.classList.remove('in-view');
  //   }
  //   if ((element_bottom_position3 >= window_top_position) &&
  //     (element_top_position3 <= window_bottom_position)) {
  //     this.step3.nativeElement.classList.add('in-view');
  //   } else {
  //     this.step3.nativeElement.classList.remove('in-view');
  //   }
  // }

  scrollToBottom(): void {
    let max = document.documentElement.scrollHeight;
    this.pos = (document.documentElement.scrollTop || document.body.scrollTop) + document.documentElement.offsetHeight;

  }

}
