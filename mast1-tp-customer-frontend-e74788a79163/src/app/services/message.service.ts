import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { API_END_POINT } from '../constant/api.constant';
import * as socketIo from 'socket.io-client';
import { environment } from './../../environments/environment';
@Injectable({
    providedIn: 'root'
})
export class MessageService {
    public ws: any;
    constructor(private http: HttpClient) { }

    sellerList(): Observable<any> {
        return this.http.post(`${API_END_POINT.seller_list}`, {});
    }
    sendMessage(data: any): Observable<any> {
        return this.http.post(`${API_END_POINT.customer_chat}`, data);
    }
    messageList(request: any) {
        return this.http.post(`${API_END_POINT.customer_seller_message}`, request);
    }
  
    initSocket(): void {
        const _data = JSON.parse(window.localStorage.getItem('tu'));
        if (!this.ws && _data && _data.data) {
            this.ws = socketIo(environment.socketUrl, {
                reconnection: true,
                reconnectionDelay: 1000,
                transports: ["websocket", 'polling']
            });
            this.ws.emit('join', {
                uid: _data.data.id,
                type: 'customer'
            });
        }
    }
    closeSocket() {
        if (this.ws) {
            this.ws.close();
            this.ws = null;
        }
    }
    sendEvent(event: any, data: any) {
        this.ws.emit(event, data);
    }

    onEvent(event: any): Observable<any> {
        return new Observable<any>(observer => {
            if (this.ws) {
                this.ws.on(event, (data) => {
                    observer.next(data)
                })
            } else {
                observer.next(null)
            }
        });
    }
}
