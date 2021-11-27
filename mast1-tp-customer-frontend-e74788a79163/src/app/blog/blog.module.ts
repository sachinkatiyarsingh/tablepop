import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Routes } from '@angular/router';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { CarouselModule } from 'ngx-owl-carousel-o';
import { AppPaginatorodule } from '../component/paginator/paginator.module';
import { BlogComponent } from './blog.component';
import { BlogDetailComponent } from './blog-detail/blog-detail.component';
const routes: Routes = [
    {
        path: '',
        component: BlogComponent
    },
    {
        path: 'detail/:id',
        component: BlogDetailComponent
    }
];
@NgModule({
    declarations: [
        BlogComponent,
        BlogDetailComponent,
    ],
    imports: [CommonModule, CarouselModule, AppPaginatorodule, FormsModule, ReactiveFormsModule.withConfig({ warnOnNgModelWithFormControl: 'never' }), RouterModule.forChild(routes)],
    providers: [],
    entryComponents: []
})
export class AppBlogModule { }
