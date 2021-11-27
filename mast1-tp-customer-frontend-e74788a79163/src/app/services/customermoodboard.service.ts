import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, BehaviorSubject } from 'rxjs';
import { API_END_POINT } from '../constant/api.constant';
@Injectable({
    providedIn: 'root'
})
export class customermoodboard {
    constructor(private http: HttpClient) { }

    moodBoardList(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.customer_moodboard_list}`, { eventId: id });
    }
    moodBoardAlbum(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.customer_moodboard_album}`, { moodboardId: id });
    }
    moodBoardImageSelect(status: any, id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.select_album_image}`, { status: status, imageId: id });
    }
}
