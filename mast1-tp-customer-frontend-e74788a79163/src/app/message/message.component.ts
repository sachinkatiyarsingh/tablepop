import { Component, OnInit, ElementRef, ViewChild } from '@angular/core';
import { MessageService } from '../services/message.service';
import { AlertService } from '../services/alert.service';
import { Router } from '@angular/router';
@Component({
    selector: 'app-message',
    templateUrl: './message.component.html',
    styleUrls: ['./message.component.css']
})
export class AppMessageComponent implements OnInit {
    //@ViewChild('msgList') private msgList: ElementRef;

    //imageUrl: any = '';
    sellerList: any[] = [];
    //message: any = '';
    selectedSeller: any;
    //messageList: any[] = [];
    //totalPage: any = 0;
    // pageNo: any = 1;
    //msgFile: any[] = [];

    isSelectSeller: boolean = false;
    viewProfile: boolean = false;
    constructor(private messageService: MessageService, private alertService: AlertService, private router: Router,) { }

    ngOnInit(): void {
        this.alertService.isShowHeader(true);
        this.alertService.isShowFooter(true);
        this.alertService.setHeaderClass('light');
        this.getSellerList();
        // this.messageService.onEvent('new_group')
        // .subscribe((res: any) => {
        //     this.addGroup(res);
        // });
    }

    // addGroup(data:any){
    //     if(data){
    //         this.sellerList.push(data);
    //     }
    //   }

    AddNotification(data: any) {
        if (data) {
            let _find = this.sellerList.find((x) => x.groupId == data.groupId);
            if (_find) {
                _find['nm'] = _find['nm'] || 0 + 1;
            }
            this.sellerList.sort((a, b) => ((a.nm || 0) > (b.nm || 0)) ? -1 : 1);
        }
    }
    getSellerList() {
        this.messageService.sellerList().subscribe((res: any) => {
            if (res.status) {
                this.sellerList = res.data || [];
                if (this.sellerList.length > 0) {
                    this.selectSeller(this.sellerList[0]);
                }
            }
        })
    }
    selectSeller(data: any) {
        this.isSelectSeller = false;
        this.selectedSeller = data;
        delete data['nm'];
        setTimeout(() => {
            this.isSelectSeller = true;
        }, 1000);
        //this.message = '';
        //this.messageList = [];
        //this.pageNo = 1;
        //this.getMessageList(true);
    }
    // getMessageList(isScrollBottom: boolean) {
    //     this.messageService.messageList({ groupId: this.selectedSeller.groupId, pageNo: this.pageNo }).subscribe((res: any) => {
    //         if (res.status) {
    //             this.totalPage = res.data ? res.data.totalPage : 0;
    //             this.messageList = (res.data ? res.data.chat || [] : []).concat(this.messageList);
    //             this.imageUrl = res.data.imageUrl;
    //             if (isScrollBottom) {
    //                 this.scrollToBottom();
    //             }
    //         }
    //     })
    // }

    // sendMessage() {
    //     let formData: FormData = new FormData();
    //     formData.append('groupId', this.selectedSeller.groupId);
    //     formData.append('message', this.message);
    //     if (this.msgFile.length > 0) {
    //         for (let i = 0; i < this.msgFile.length; i++) {
    //             formData.append('msgFile[]', this.msgFile[i].file, this.msgFile[i].file.name);
    //         }
    //     }
    //     this.messageService.sendMessage(formData).subscribe((res: any) => {
    //         if (res.status) {
    //             this.messageService.sendEvent('new_message', { messageId: res.data.id });
    //             this.messageList.push(res.data);
    //             this.message = '';
    //             this.msgFile = [];
    //             this.scrollToBottom();
    //         }
    //     })
    // }
    // scrollToBottom(): void {
    //     try {
    //         setTimeout(() => {
    //             this.msgList.nativeElement.scrollTop = this.msgList.nativeElement.scrollHeight;
    //         })
    //     } catch (err) { }
    // }
    getMoreSellers() {
        console.log('ss')
    }
    // getMoreChats() {
    //     if (this.totalPage > this.pageNo) {
    //         this.pageNo = this.pageNo + 1;
    //         this.getMessageList(false);
    //     }
    // }
    // fileChangeListener(files: any, fileInput: any) {
    //     if (this.msgFile.length + files.length > 5) {
    //         this.alertService.error('Max 5 file are allowed.');
    //         fileInput.value = '';
    //         return;
    //     }
    //     for (let i = 0; i < files.length; i++) {
    //         const myReader: FileReader = new FileReader();
    //         let _data = {
    //             file: files[i]
    //         }
    //         myReader.onloadend = (e) => {
    //             _data["img"] = myReader.result;
    //         };
    //         myReader.readAsDataURL(files[i]);
    //         this.msgFile.push(_data);
    //     }
    //     fileInput.value = '';
    // }
    // remove(index: any) {
    //     this.msgFile.splice(index, 1);
    // }
    view_profile(id: any) {
        this.router.navigate(['/event', id])
    }
}
