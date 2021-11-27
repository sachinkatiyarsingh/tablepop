import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, BehaviorSubject } from 'rxjs';
import { API_END_POINT } from '../constant/api.constant';
@Injectable({
    providedIn: 'root'
})
export class ServiceBudgetService {
    constructor(private http: HttpClient) { }

    questionnaire(data: FormData): Observable<any> {
        return this.http.post(`${API_END_POINT.questionnaire}`, data);
    }
    questionnaireDetails(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.questionnaire_Details}`, { questionnaireId: id });
    }
    questionnaireUpdate(data: FormData): Observable<any> {
        return this.http.post(`${API_END_POINT.questionnaire_Update}`, data);
    }
    imageDelete(id: any, qid: any): Observable<any> {
        return this.http.post(`${API_END_POINT.image_delete}`, { imageId: id, questionnaireId: qid });
    }
    questionnairePlanner(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.questionnaire_planner}`, { questionnaireId: id });
    }
    questionnairePlannerDetail(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.questionnaire_planner_detail}`, { sellerId: id });
    }
    customerPayment(data: any) {
        return this.http.post(`${API_END_POINT.customer_payment}`, data);
    }
    getCountry(): Observable<any> {
        return this.http.post(`${API_END_POINT.country}`, {});
    }
    themes(): Observable<any> {
        return this.http.post(`${API_END_POINT.themes}`, {})
    }
    vendorList(req: any) {
        return this.http.post(`${API_END_POINT.customer_vendor_list}`, req)
    }
    getVendorDetail(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.customer_vendor_profile}`, { vendorId: id });
    }
    getVendorProduct(req: any): Observable<any> {
        return this.http.post(`${API_END_POINT.customer_vendor_product}`, req);
    }
    productPayment(req: any): Observable<any> {
        return this.http.post(`${API_END_POINT.customer_product_payment}`, req);
    }
    paymentDetail(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.payment_details}`, { planId: id });
    }
    submitReview(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.rating}`, id);
    }
    editReview(data: any): Observable<any> {
        return this.http.post(`${API_END_POINT.editrating}`, data);
    }
    eventType(): Observable<any> {
        return this.http.post(`${API_END_POINT.event_type}`, {});
    }
    venue(): Observable<any> {
        return this.http.post(`${API_END_POINT.venue}`, {});
    }
    serviceCategory(): Observable<any> {
        return this.http.post(`${API_END_POINT.service_category}`, {});
    }
    serviceSubCategory(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.service_subcategory}`, { categoryId: id });
    }
    productById(id: any): Observable<any> {
        return this.http.post(`${API_END_POINT.product_details}`, { productId: id });
    }
}
