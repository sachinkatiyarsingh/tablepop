import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, BehaviorSubject } from 'rxjs';
import { API_END_POINT } from '../constant/api.constant';
import { Router } from '@angular/router';
@Injectable({
    providedIn: 'root'
})
export class AuthService {
    private isLogin = new BehaviorSubject(false);
    private currentUser = new BehaviorSubject(null);
    private showLogin = new BehaviorSubject<boolean>(false);
    private showSignup = new BehaviorSubject<boolean>(false);
    public currentLoginStatus = this.isLogin.asObservable();
    public currentUserData = this.currentUser.asObservable();
    public isShowLogin = this.showLogin.asObservable();
    public isShowSignup = this.showSignup.asObservable();
    constructor(private http: HttpClient, private router: Router) { }
    saveUserDetail(res: any) {
        window.localStorage.setItem('tu', JSON.stringify(res));
        this.currentUser.next(res.data);
        this.isLogin.next(true);
        this.router.navigate(['/dashboard']);
        return res;
    }
    checkAuth() {
        if (window.localStorage.getItem('tu')) {
            const _data = JSON.parse(window.localStorage.getItem('tu'));
            this.currentUser.next(_data.data);
            this.isLogin.next(true);
        }
        else {
            this.isLogin.next(false);
            this.currentUser.next(null);
        }
    }
    updateUserDetail(res: any) {
        var _data = JSON.parse(window.localStorage.getItem('tu'));
        _data.data = res;
        window.localStorage.setItem('tu', JSON.stringify(_data));
        this.currentUser.next(_data.data);
    }
    setShowLogin(isShow: boolean) {
        this.showLogin.next(isShow);
    }
    setShowSignup(isShw: boolean) {
        this.showSignup.next(isShw);
    }
    getCurrentUser() {
        return this.currentUser.value;
    }
    login(data: any): Observable<any> {
        return this.http.post(`${API_END_POINT.login}`, data);
    }
    signup(data: any): Observable<any> {
        return this.http.post(`${API_END_POINT.signUp}`, data);
    }

    socialRegister(data: any): Observable<any> {
        return this.http.post(`${API_END_POINT.social_register}`, data);
    }
    logout() {
        window.localStorage.removeItem('tu');
        this.isLogin.next(false);
        this.currentUser.next(null);
        this.router.navigate(["/"]);
    }
    getCountry(): Observable<any> {
        return this.http.post(`${API_END_POINT.country}`, {});
    }
    getState(countryId: any): Observable<any> {
        return this.http.post(`${API_END_POINT.states}`, { country_id: countryId });
    }
    ForgotPassword(request: any): Observable<any> {
        return this.http.post(`${API_END_POINT.forgot}`, request)
    }
    resetPassword(token: any, request: any): Observable<any> {
        return this.http.post(`${API_END_POINT.resetPassword}/${token}`, request)
    }
    profile(data: any) {
        return this.http.post(`${API_END_POINT.profile}`, data)
    }
    customerChangePassword(request: any): Observable<any> {
        return this.http.post(`${API_END_POINT.customer_change_password}`, request)
    }
    address(): Observable<any> {
        return this.http.post(`${API_END_POINT.address}`, {})
    }
    addAddress(data: any): Observable<any> {
        return this.http.post(`${API_END_POINT.address_add}`, data)
    }
    editAddress(data: any): Observable<any> {
        return this.http.post(`${API_END_POINT.address_edit}`, data)
    }
    deleteAddress(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.address_delete}`, { addressId: id })
    }
    subscription(data: any): Observable<any> {
        return this.http.post(`${API_END_POINT.subscription}`, data)
    }
    subscriptionForm(data: any): Observable<any> {
        return this.http.post(`${API_END_POINT.subscriptionForm}`, data)
    }
}
