import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, BehaviorSubject } from 'rxjs';
import { API_END_POINT } from '../constant/api.constant';
@Injectable({
    providedIn: 'root'
})
export class BlogService {
    constructor(private http: HttpClient) { }

    blog(req: any): Observable<any> {
        return this.http.post(`${API_END_POINT.blog}`, req);
    }
    blogDetail(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.blog_details}`, { blogId: id });
    }

}
