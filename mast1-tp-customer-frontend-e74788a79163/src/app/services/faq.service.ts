import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { API_END_POINT } from '../constant/api.constant';
@Injectable({
    providedIn: 'root'
})
export class FaqService {
    constructor(private http: HttpClient) { }

    faq(pageNo): Observable<any> {
        return this.http.post(`${API_END_POINT.faq}`, pageNo);
    }

}
