import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { API_END_POINT } from '../constant/api.constant';
@Injectable({
    providedIn: 'root'
})
export class ContactUsService {
    constructor(private http: HttpClient) { }

    contactUs(formData: any): Observable<any> {
        return this.http.post(`${API_END_POINT.contact_us}`, formData);
    }

}
