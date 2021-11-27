import { Component, OnInit, ElementRef, ViewChild, Input, Output, EventEmitter } from '@angular/core';
import { AlertService } from '../../services/alert.service';
import { MessageService } from '../../services/message.service';

@Component({
    selector: 'app-chat-box',
    templateUrl: './chat-box.component.html',
    styleUrls: ['./chat-box.component.css']
})
export class AppChatBoxComponent implements OnInit {
    @ViewChild('msgList') private msgList: ElementRef;
    @Input() groupId: any
    @Output() getNotification: EventEmitter<any> = new EventEmitter<any>();
    imageUrl: any = '';
    message: any = '';
    messageList: any[] = [];
    totalPage: any = 0;
    pageNo: any = 1;
    msgFile: any[] = [];
    constructor(private alertService: AlertService, private messageService: MessageService) { }

    ngOnInit(): void {
        this.messageService.initSocket();
        this.getMessageList(false);
        this.messageService.onEvent('new_message')
            .subscribe((res: any) => {
                this.AddNotification(res);
            });
           
    }
    ngOnDestroy() {
        this.messageService.closeSocket();
    }
    
    AddNotification(data: any) {
        if (data.groupId == this.groupId) {
            this.messageList.push(data);
            this.scrollToBottom();
        }
        else {
            this.getNotification.emit(data);
        }
    }
    getMessageList(isScrollBottom: boolean) {
        this.messageService.messageList({ groupId: this.groupId, pageNo: this.pageNo }).subscribe((res: any) => {
            if (res.status) {
                this.totalPage = res.data ? res.data.totalPage : 0;
                this.messageList = (res.data ? res.data.chat || [] : []).concat(this.messageList);
                this.imageUrl = res.data.imageUrl;
                if (isScrollBottom) {
                    this.scrollToBottom();
                }
            }
        })
    }

    sendMessage() {
        let formData: FormData = new FormData();
        formData.append('groupId', this.groupId);
        formData.append('message', this.message);
        if (this.msgFile.length > 0) {
            for (let i = 0; i < this.msgFile.length; i++) {
                formData.append('msgFile[]', this.msgFile[i].file, this.msgFile[i].file.name);
            }
        }
        this.messageService.sendMessage(formData).subscribe((res: any) => {
            if (res.status) {
                this.messageService.sendEvent('new_message', { messageId: res.data.id });
                this.messageList.push(res.data);
                this.message = '';
                this.msgFile = [];
                this.scrollToBottom();
            }
        })
    }
    scrollToBottom(): void {
        try {
            setTimeout(() => {
                this.msgList.nativeElement.scrollTop = this.msgList.nativeElement.scrollHeight;
            })
        } catch (err) { }
    }
    getMoreChats() {
        if (this.totalPage > this.pageNo) {
            this.pageNo = this.pageNo + 1;
            this.getMessageList(false);
        }
    }
    fileChangeListener(files: any, fileInput: any) {
        if (this.msgFile.length + files.length > 5) {
            this.alertService.error('Max 5 file are allowed.');
            fileInput.value = '';
            return;
        }
        for (let i = 0; i < files.length; i++) {
            const myReader: FileReader = new FileReader();
            let _data = {
                file: files[i],
                type: files[i].type
            }
            myReader.onloadend = (e) => {
                _data["img"] = myReader.result;
            };
            myReader.readAsDataURL(files[i]);
            this.msgFile.push(_data);
        }
        fileInput.value = '';
    }
    remove(index: any) {
        this.msgFile.splice(index, 1);
    }
}
