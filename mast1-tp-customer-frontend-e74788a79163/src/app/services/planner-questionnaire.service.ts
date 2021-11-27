import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, BehaviorSubject } from 'rxjs';
import { API_END_POINT } from '../constant/api.constant';
@Injectable({
    providedIn: 'root'
})
export class plannerQuestionnaire {
    constructor(private http: HttpClient) { }

    planner_questionnaire(data: FormData): Observable<any> {
        return this.http.post(`${API_END_POINT.planner_questionnaire}`, data);
    }
  
}
