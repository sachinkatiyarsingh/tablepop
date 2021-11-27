import { Component, OnInit, Input } from '@angular/core';


@Component({
    selector: 'app-message-box',
    templateUrl: './message-box.component.html',
    styleUrls: ['./message-box.component.css']
})
export class AppMessageBoxComponent implements OnInit {
    @Input() data: any;
    @Input() imageUrl: any;
    message: any;
    constructor() { }

    ngOnInit(): void {
        this.message = { ...this.data };
        delete this.message.msgFile;
        if (this.data.msgFile && this.data.msgFile.length > 0) {
            this.message['msgFile'] = JSON.parse(this.data.msgFile);
            this.message.msgFiles = [];
            this.message.msgFile.forEach(element => {
                var exten = element.split('.');
                exten = exten[exten.length - 1];
                if (exten == "docx" || exten == "pdf" || exten == "xlsx" || exten == "odt" || exten == "ods" || exten == "ppt" || exten == "txt") {
                    this.message.msgFiles.push({ 'iconShow': true, 'file': element })
                }
                else {
                    this.message.msgFiles.push({ 'iconShow': false, 'file': element })
                }
            });
        }
    }
}
