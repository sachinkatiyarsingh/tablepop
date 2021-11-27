import { Injectable } from '@angular/core';
import { Router, NavigationStart } from '@angular/router';
import { Observable, BehaviorSubject } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class AlertService {
    private subject = new BehaviorSubject<any>(null);
    private keepAfterNavigationChange = false;
    private loader = new BehaviorSubject<boolean>(false);
    private showHeader = new BehaviorSubject<boolean>(false);
    private showFooter = new BehaviorSubject<boolean>(false);
    private headerClass = new BehaviorSubject<string>('');

    constructor(private router: Router) {
        // clear alert message on route change
        router.events.subscribe(event => {
            if (event instanceof NavigationStart) {
                if (this.keepAfterNavigationChange) {
                    // only keep for a single location change
                    this.keepAfterNavigationChange = false;
                } else {
                    // clear alert
                    this.subject.next(null);
                }
            }
        });
    }

    success(message: string, keepAfterNavigationChange = false) {
        this.keepAfterNavigationChange = keepAfterNavigationChange;
        this.subject.next({ error: false, message: { title: message } });
    }

    error(message: string, keepAfterNavigationChange = false) {
        this.keepAfterNavigationChange = keepAfterNavigationChange;
        this.subject.next({ error: true, message: { title: message } });
    }

    showLoader(show: boolean) {
        this.loader.next(show);
    }
    setHeaderClass(cssClass: string) {
        this.headerClass.next(cssClass);
    }
    getHeaderClass(): Observable<string> {
        return this.headerClass.asObservable();
    }
    isShowHeader(show: boolean) {
        this.showHeader.next(show);
    }
    isShowFooter(show: boolean) {
        this.showFooter.next(show);
    }
    isHeaderShow(): Observable<boolean> {
        return this.showHeader.asObservable();
    }
    isFooterShow(): Observable<boolean> {
        return this.showFooter.asObservable();
    }

    getMessage(): Observable<any> {
        return this.subject.asObservable();
    }
    getLoader(): Observable<boolean> {
        return this.loader.asObservable();
    }
    gotoTop() {
        window.scroll({
            top: 0,
            left: 0,
            behavior: 'smooth'
        });
    }
}
