import 'dart:collection';

import 'package:elmenu/src/DataModels/Offer.dart';
import 'package:elmenu/src/Widgets/FloatedCard.dart';
import 'package:floating_pullup_card/floating_layout.dart';
import 'package:floating_pullup_card/pullup_card.dart';
import 'package:floating_pullup_card/types.dart';
import 'package:flutter/material.dart';

import '../BLoC.dart';


class OffersPageView extends StatefulWidget {
  BLoC bLoC;


  OffersPageView({Key key,this.bLoC});
  @override
  _OffersPageViewState createState() => _OffersPageViewState();
}

class _OffersPageViewState extends State<OffersPageView> {


  FloatingPullUpState _floatingCardState = FloatingPullUpState.hidden;
  bool _customDragHandle = true;
  bool _customCollapsedOffset = false;
  bool _autoPadd = true;
  bool _withOverlay = true;
  bool _customUncollapsedOffset = false;

  String title = '';
  String discrption='';
  String photo='';
  String price='';
  bool is_discount = false;

  @override
  Widget build(BuildContext context) {
    return RefreshIndicator(
      onRefresh: widget.bLoC.RefreshOffers,
      child: FloatingPullUpCardLayout(
          dismissable: true,
          onOutsideTap: (){
            setState(() {
              _floatingCardState = FloatingPullUpState.hidden;
            });
          },
          state: _floatingCardState,
          dragHandleBuilder: _customDragHandle ? _customDragHandleBuilder : null,
          cardBuilder: _customCardBuilder,
          collpsedStateOffset:
          _customCollapsedOffset ? (maxHeight, _) => maxHeight * .75 : null,
          uncollpsedStateOffset:
          _customUncollapsedOffset ? (maxHeight) => maxHeight * .05 : null,
          autoPadding: _autoPadd,
          withOverlay: _withOverlay,
          body: Container(
            // padding: EdgeInsets.all(16),
            child: SingleChildScrollView(
              child: Column(
                children: <Widget>[
                  Stack(
                    alignment: Alignment.bottomCenter,
                    children: [
                      Container(
                        width: MediaQuery.of(context).size.width,
                        height: 120,
                        decoration: BoxDecoration(
                          //borderRadius: BorderRadius.circular(100),
                            image: DecorationImage(
                              image: NetworkImage(photo),
                              fit: BoxFit.cover,
                            )
                        ),
                      ),
                      Container(
                        decoration: BoxDecoration(
                          gradient: LinearGradient(
                            colors: [Color(0x00f5f5f5),Color(0xfff5f5f5)],
                            begin: Alignment.topCenter,
                            end: Alignment.bottomCenter,
                          ),
                        ),
                        height: 70,
                      ),
                    ],
                  ),
                  SizedBox(
                    height: 10,
                  ),
                  Container(
                    decoration: BoxDecoration(
                        border: Border.all(width: 3,color: Colors.red.shade300),
                        borderRadius: BorderRadius.all(Radius.circular(5))
                    ),
                    child: Text(
                      ' ${title} ',
                      overflow: TextOverflow.fade,
                      softWrap: false,
                      style: TextStyle(color: Theme.of(context).primaryColor,fontSize: 17,fontWeight: FontWeight.bold,fontFamily: 'Cairo'),
                    ),
                  ),
                  SizedBox(
                    height: 10,
                  ),
                  Padding(
                    padding: const EdgeInsets.all(5.0),
                    child: Container(

                      child: Text(
                        ' ${discrption} ',
                        //overflow: TextOverflow.fade,
                        softWrap: true,
                        style: TextStyle(color: Colors.black87,fontSize: 13,fontFamily: 'Baloo2'),
                      ),
                    ),
                  ),
                  SizedBox(height: 10,),
                  Container(
                    decoration: BoxDecoration(
                      border: Border(bottom: BorderSide(width: 3,color: Colors.red.shade300)),
                    ),
                    child: Text(
                      is_discount ?' Discount: ${price} ':' Price: ${price} ',
                      overflow: TextOverflow.fade,
                      softWrap: false,
                      style: TextStyle(color: Theme.of(context).primaryColor,fontSize: 13,fontWeight: FontWeight.bold,fontFamily: 'Cairo'),
                    ),
                  ),
                ],
              ),
            ),
          ),
          child: Container(
            child: Column(
              children: [
                Expanded(
                    child: StreamBuilder<UnmodifiableListView<offer>>(
                        stream: widget.bLoC.Offer,
                        initialData: UnmodifiableListView<offer>([]),
                        builder: (context, snapshot) => ListView(
                          children: snapshot.data.map(menu_item).toList(),
                        )))
              ],
            ),
          )
      ),
    );
  }

  Widget menu_item(offer item){
    return Padding(
      padding: const EdgeInsets.only(top:7.0,bottom: 7.0, left: 8.0,right: 8.0),
      child: InkWell(
        onTap: (){
          setState(() {
            _floatingCardState = FloatingPullUpState.uncollapsed;
            title = item.offer_title;
            discrption = item.offer_detalis;
            price = item.is_discount=='0'?item.offer_price:item.offer_discount;
            is_discount= item.is_discount=='0'?false:true;
            photo = item.offer_photo;
          });
        },
        child: Container(
          decoration: BoxDecoration(
            borderRadius: BorderRadius.all(Radius.circular(20)),
            color: Colors.white,
            boxShadow: [BoxShadow(
              color: Colors.grey.withOpacity(0.5),
              spreadRadius: 1,
              blurRadius: 10,
              offset: Offset(0,3),
            )],
          ),
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Hero(
                tag: item.offer_title,
                child: Container(
                  width: 120,
                  height: 120,
                  decoration: BoxDecoration(
                      borderRadius: BorderRadius.only(topLeft: Radius.circular(20),bottomLeft: Radius.circular(20)),
                      image: DecorationImage(
                          image: NetworkImage(item.offer_photo),
                          fit: BoxFit.cover
                      )
                  ),
                ),
              ),
              Container(
                width: MediaQuery.of(context).size.width-140,
                height: 120,
                child: Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 15,vertical: 10),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.center,
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    mainAxisSize: MainAxisSize.max,
                    children: <Widget>[
                      Expanded(
                        flex: 3,
                        child: Column(
                          children: <Widget>[
                            Container(
                              decoration: BoxDecoration(
                                  border: Border.all(width: 3,color: Colors.red.shade300),
                                  borderRadius: BorderRadius.all(Radius.circular(5))
                              ),
                              child: Text(
                                ' ${item.offer_title} ',
                                overflow: TextOverflow.fade,
                                softWrap: false,
                                style: TextStyle(color: Theme.of(context).primaryColor,fontSize: 14,fontWeight: FontWeight.bold,fontFamily: 'Cairo'),
                              ),
                            ),
                            SizedBox(
                              height: 7,
                            ),
                            Text(
                              item.offer_detalis,
                              overflow: TextOverflow.fade,
                              softWrap: true,
                              maxLines: 2,
                              style: Theme.of(context).textTheme.caption,
                            ),
                            SizedBox(
                              height: 4,
                            ),
                          ],
                        ),
                      ),
                      SizedBox(width: 15,),
                      Expanded(
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: <Widget>[
                            Text(
                              item.is_discount=='0'?"price: ${item.offer_price}":"Discount: ${item.offer_discount}",
                              //overflow: TextOverflow.fade,
                              maxLines: 1,
                              softWrap: false,
                              style: TextStyle(color: Colors.grey,fontFamily: 'cairo',fontWeight: FontWeight.bold),
                            ),
                            Text(
                              "Press here for More Details",
                              overflow: TextOverflow.fade,
                              maxLines: 1,
                              softWrap: false,
                              style: TextStyle(color: Colors.blue,fontFamily: 'cairo',fontWeight: FontWeight.bold,fontSize: 10),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  DragHandleBuilder _customDragHandleBuilder =
      (context, constraints, beingDragged) {
    return CustomDrag(
      beingDragged: beingDragged,
    );
  };

  FloatingCardBuilder _customCardBuilder =
      (context, constraints, dragHandler, body, beingDragged) {
    return CustomCard(
      dragHandle: dragHandler,
      constraints: constraints,
      body: body,
      beingDragged: beingDragged,
    );
  };

}

