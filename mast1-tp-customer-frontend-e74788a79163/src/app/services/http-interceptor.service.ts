import { Injectable } from '@angular/core';
import {
    HttpEvent, HttpInterceptor, HttpHandler, HttpRequest, HttpResponse, HttpErrorResponse
} from '@angular/common/http';
import { AuthService } from './auth.service';
import { AlertService } from './alert.service';
import { Observable, throwError } from 'rxjs';
import { map, catchError } from 'rxjs/operators';
import { Router } from '@angular/router';

@Injectable({
    providedIn: 'root'
})
export class InterceptorService implements HttpInterceptor {

    constructor(private auth: AuthService, private alertService: AlertService, private router: Router) { }

    intercept(
        req: HttpRequest<any>,
        next: HttpHandler
    ): Observable<HttpEvent<any>> {
        this.alertService.showLoader(true);
        let tokenReq: any;
        const userToken = JSON.parse(window.localStorage.getItem('tu'));
        if (userToken && userToken.token) {
            tokenReq = req.clone({
                setHeaders: {
                    Authorization: `Bearer ${userToken.token}`, 'Cache-Control': 'no-cache',
                    Pragma: 'no-cache',
                }
            });
        }
        else {
            tokenReq = req.clone();
        }

        return next.handle(tokenReq).pipe(
            map((event: HttpEvent<any>) => {
                if (event instanceof HttpResponse) {
                    this.alertService.showLoader(false);
                }
                return event;
            }),
            catchError((error: HttpErrorResponse) => {
                this.alertService.showLoader(false);
                this.showError(error);
                return throwError(error);
            })
        );
    }

    private showError(err: any) {
        if (err.status === 401) {
            this.alertService.error('Your session has expired.', true);
            window.localStorage.removeItem('tu');
            window.localStorage.clear();
            this.router.navigate(["/"]);
        } else if (err.status === 504) {
            this.alertService.error('No internet connection. Please Try Again.', true);
        }
        else if (err.status == 500) {
            this.alertService.error('Something went wrong please try again.');
        }
    }
}
