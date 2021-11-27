import { Component, OnChanges, Input,EventEmitter,Output} from '@angular/core';

@Component({
  selector: 'app-paginator',
  templateUrl: './paginator.component.html',
  styleUrls: ['./paginator.component.css']
})
export class PaginatorComponent implements OnChanges {

  @Input() public pageSize: number;
  @Input() public totalCount: number;
  @Input() public pageNumber: number;
  @Output() public onPageSelect = new EventEmitter();
  pageLength: number = 0;
  constructor() { }

  ngOnChanges(): void {
    this.pageLength = Math.ceil(this.totalCount / this.pageSize);    
  }
  pageSelect(pageNumber){
    this.onPageSelect.emit(pageNumber);
  }
}
