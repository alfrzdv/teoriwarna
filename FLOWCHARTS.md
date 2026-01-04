# TeoriWarna E-Commerce - System Flowcharts

## 1. Customer Order Flow (Complete Journey)

```mermaid
%%{init: {'theme':'base', 'themeVariables': { 'primaryColor':'#ffffff','primaryTextColor':'#1a1a1a','primaryBorderColor':'#e0e0e0','lineColor':'#757575','secondaryColor':'#fafafa','tertiaryColor':'#f5f5f5','background':'#ffffff','fontSize':'14px','fontFamily':'Georgia, serif'}}}%%

flowchart TD
    Start([Customer Visits Site]) --> Browse[Browse Products]
    Browse --> ProductDetail{View Product<br/>Detail?}

    ProductDetail -->|Yes| ViewProduct[View Product Page]
    ProductDetail -->|No| Browse

    ViewProduct --> AddCart{Add to Cart<br/>or Buy Now?}

    AddCart -->|Add to Cart| CartAdded[Product Added to Cart]
    AddCart -->|Buy Now| DirectCheckout[Go to Checkout]

    CartAdded --> ContinueShopping{Continue<br/>Shopping?}
    ContinueShopping -->|Yes| Browse
    ContinueShopping -->|No| ViewCart[View Shopping Cart]

    ViewCart --> SelectItems[Select Items for Checkout]
    SelectItems --> Checkout[Proceed to Checkout]

    DirectCheckout --> CheckAuth{User<br/>Logged In?}
    Checkout --> CheckAuth

    CheckAuth -->|No| Login[Login/Register]
    CheckAuth -->|Yes| FillShipping[Fill Shipping Information]

    Login --> FillShipping

    FillShipping --> SelectShipping[Select Shipping Method]
    SelectShipping --> SelectPayment[Select Payment Method]
    SelectPayment --> ReviewOrder[Review Order Summary]

    ReviewOrder --> ConfirmOrder{Confirm<br/>Order?}
    ConfirmOrder -->|No| FillShipping
    ConfirmOrder -->|Yes| CreateOrder[Create Order]

    CreateOrder --> CheckStock{Stock<br/>Available?}
    CheckStock -->|No| StockError[Stock Insufficient Error]
    StockError --> ViewCart

    CheckStock -->|Yes| OrderCreated[Order Created - Status: Pending]
    OrderCreated --> PaymentPage[Redirect to Payment Page]

    PaymentPage --> PaymentMethod{Payment<br/>Method?}

    PaymentMethod -->|Midtrans| OpenSnap[Open Midtrans Snap]
    PaymentMethod -->|COD| CODConfirm[COD Confirmed]

    OpenSnap --> SelectPaymentType[Select Payment Type:<br/>Bank Transfer/E-Wallet/<br/>Credit Card/Store]

    SelectPaymentType --> MakePayment[Complete Payment]

    MakePayment --> PaymentSuccess{Payment<br/>Success?}

    PaymentSuccess -->|Yes| WebhookReceived[Midtrans Sends Webhook]
    PaymentSuccess -->|No| PaymentFailed[Payment Failed]

    WebhookReceived --> UpdatePayment[Update Payment Status: Paid]
    UpdatePayment --> UpdateOrder[Update Order Status: Processing]

    CODConfirm --> UpdateOrder

    PaymentFailed --> RetryPayment{Retry<br/>Payment?}
    RetryPayment -->|Yes| PaymentPage
    RetryPayment -->|No| CancelOrder[Cancel Order]

    UpdateOrder --> AdminProcess[Admin Processes Order]
    AdminProcess --> PackOrder[Pack Order]
    PackOrder --> ShipOrder[Ship Order - Status: Shipped]
    ShipOrder --> AddTracking[Add Tracking Number]

    AddTracking --> CustomerNotified[Customer Notified]
    CustomerNotified --> WaitDelivery[Wait for Delivery]

    WaitDelivery --> Delivered{Order<br/>Delivered?}
    Delivered -->|Yes| ConfirmReceived[Customer Confirms Receipt]
    Delivered -->|No| WaitDelivery

    ConfirmReceived --> OrderCompleted[Order Status: Delivered]

    OrderCompleted --> ReviewProduct{Write<br/>Review?}
    ReviewProduct -->|Yes| WriteReview[Submit Product Review]
    ReviewProduct -->|No| End([Order Complete])

    WriteReview --> End
    CancelOrder --> End

    classDef startEnd fill:#1a1a1a,stroke:#e0e0e0,stroke-width:2px,color:#ffffff
    classDef success fill:#4caf50,stroke:#388e3c,stroke-width:2px,color:#ffffff
    classDef warning fill:#ff9800,stroke:#f57c00,stroke-width:2px,color:#ffffff
    classDef error fill:#f44336,stroke:#d32f2f,stroke-width:2px,color:#ffffff
    classDef process fill:#ffffff,stroke:#bdbdbd,stroke-width:1.5px,color:#424242
    classDef decision fill:#f5f5f5,stroke:#bdbdbd,stroke-width:2px,color:#1a1a1a
    
    class Start,End startEnd
    class OrderCreated,UpdateOrder success
    class PaymentPage warning
    class PaymentFailed,CancelOrder,StockError error
    class Browse,ViewProduct,CartAdded,ViewCart,SelectItems,DirectCheckout,Login,FillShipping,SelectShipping,SelectPayment,ReviewOrder,CreateOrder,OpenSnap,SelectPaymentType,MakePayment,WebhookReceived,UpdatePayment,CODConfirm,AdminProcess,PackOrder,ShipOrder,AddTracking,CustomerNotified,WaitDelivery,ConfirmReceived,OrderCompleted,WriteReview process
    class ProductDetail,AddCart,ContinueShopping,CheckAuth,ConfirmOrder,CheckStock,PaymentMethod,PaymentSuccess,RetryPayment,Delivered,ReviewProduct decision
    
    linkStyle default stroke:#757575,stroke-width:1.5px
```

---

## 2. Payment Integration Flow (Midtrans Snap)

```mermaid
%%{init: {'theme':'base', 'themeVariables': { 'primaryColor':'#ffffff','primaryTextColor':'#1a1a1a','primaryBorderColor':'#e0e0e0','lineColor':'#757575','secondaryColor':'#fafafa','tertiaryColor':'#f5f5f5','background':'#ffffff','fontSize':'14px','fontFamily':'Georgia, serif'}}}%%

flowchart TD
    Start([User Clicks 'Bayar Sekarang']) --> CheckAuth{Order belongs<br/>to user?}

    CheckAuth -->|No| Error403[Return 403 Forbidden]
    CheckAuth -->|Yes| CheckPaymentStatus{Payment<br/>Status?}

    CheckPaymentStatus -->|Already Paid| AlreadyPaid[Show 'Already Paid' Message]
    CheckPaymentStatus -->|Pending| GenerateToken[Generate Snap Token Request]

    GenerateToken --> PrepareData[Prepare Transaction Data:<br/>- Order Number<br/>- Total Amount<br/>- Customer Details<br/>- Item Details]

    PrepareData --> CallMidtrans[Call Midtrans Snap API]

    CallMidtrans --> TokenReceived{Token<br/>Received?}

    TokenReceived -->|No| TokenError[Return Error to User]
    TokenReceived -->|Yes| SaveToken[Save snap_token to Database]

    SaveToken --> ReturnToken[Return Token to Frontend]
    ReturnToken --> OpenPopup[Frontend Opens Midtrans Popup]

    OpenPopup --> UserPays[User Selects Payment Method<br/>and Completes Payment]

    UserPays --> PaymentResult{Payment<br/>Result?}

    PaymentResult -->|Success| SuccessCallback[Midtrans Calls onSuccess]
    PaymentResult -->|Pending| PendingCallback[Midtrans Calls onPending]
    PaymentResult -->|Failed| FailedCallback[Midtrans Calls onError]
    PaymentResult -->|Closed| CloseCallback[Midtrans Calls onClose]

    SuccessCallback --> RedirectSuccess[Redirect to Order Page]
    PendingCallback --> RedirectPending[Redirect to Order Page]
    FailedCallback --> ShowError[Show Error Alert]
    CloseCallback --> EnableButton[Re-enable Pay Button]

    ShowError --> EnableButton

    subgraph "Webhook Process (Async)"
        WebhookStart([Midtrans Sends Webhook]) --> ReceiveNotification[Receive Notification]
        ReceiveNotification --> VerifySignature{Verify<br/>Signature?}

        VerifySignature -->|Invalid| WebhookError[Log Error & Return]
        VerifySignature -->|Valid| ParseNotification[Parse Notification Data]

        ParseNotification --> FindOrder[Find Order by order_number]
        FindOrder --> OrderExists{Order<br/>Found?}

        OrderExists -->|No| OrderNotFound[Log Error & Return]
        OrderExists -->|Yes| CheckTransactionStatus{Transaction<br/>Status?}

        CheckTransactionStatus -->|capture/settlement| ProcessSuccess[Update Payment: Paid<br/>Update Order: Processing]
        CheckTransactionStatus -->|pending| ProcessPending[Update Payment: Pending]
        CheckTransactionStatus -->|deny/expire/cancel| ProcessFailed[Update Payment: Failed<br/>Restore Stock]

        ProcessSuccess --> SendEmail[Send Confirmation Email]
        ProcessPending --> SendEmail
        ProcessFailed --> SendEmail

        SendEmail --> WebhookEnd([Return 200 OK])
    end

    RedirectSuccess --> End([End])
    RedirectPending --> End
    EnableButton --> End
    AlreadyPaid --> End
    Error403 --> End
    TokenError --> End

    classDef startEnd fill:#1a1a1a,stroke:#e0e0e0,stroke-width:2px,color:#ffffff
    classDef success fill:#4caf50,stroke:#388e3c,stroke-width:2px,color:#ffffff
    classDef error fill:#f44336,stroke:#d32f2f,stroke-width:2px,color:#ffffff
    classDef process fill:#ffffff,stroke:#bdbdbd,stroke-width:1.5px,color:#424242
    classDef decision fill:#f5f5f5,stroke:#bdbdbd,stroke-width:2px,color:#1a1a1a
    
    class Start,End,WebhookStart,WebhookEnd startEnd
    class ProcessSuccess success
    class Error403,TokenError,WebhookError,OrderNotFound,ProcessFailed error
    class GenerateToken,PrepareData,CallMidtrans,SaveToken,ReturnToken,OpenPopup,UserPays,SuccessCallback,PendingCallback,FailedCallback,CloseCallback,RedirectSuccess,RedirectPending,ShowError,EnableButton,AlreadyPaid,ReceiveNotification,ParseNotification,FindOrder,ProcessPending,SendEmail process
    class CheckAuth,CheckPaymentStatus,TokenReceived,PaymentResult,VerifySignature,OrderExists,CheckTransactionStatus decision
    
    linkStyle default stroke:#757575,stroke-width:1.5px
```

---

## 3. Admin Order Management Flow

```mermaid
%%{init: {'theme':'base', 'themeVariables': { 'primaryColor':'#ffffff','primaryTextColor':'#1a1a1a','primaryBorderColor':'#e0e0e0','lineColor':'#757575','secondaryColor':'#fafafa','tertiaryColor':'#f5f5f5','background':'#ffffff','fontSize':'14px','fontFamily':'Georgia, serif'}}}%%

flowchart TD
    Start([Admin Access Orders]) --> ViewOrders[View Orders List]

    ViewOrders --> FilterOrders{Filter by<br/>Status?}
    FilterOrders -->|Yes| ApplyFilter[Apply Status Filter]
    FilterOrders -->|No| AllOrders[Show All Orders]

    ApplyFilter --> AllOrders
    AllOrders --> SelectOrder[Select Order to Manage]

    SelectOrder --> ViewOrderDetail[View Order Details]
    ViewOrderDetail --> CheckStatus{Current<br/>Status?}

    CheckStatus -->|Pending| WaitingPayment[Waiting for Payment]
    CheckStatus -->|Processing| ProcessOrder[Process Order]
    CheckStatus -->|Shipped| TrackShipment[Track Shipment]
    CheckStatus -->|Delivered| OrderComplete[Order Completed]
    CheckStatus -->|Cancelled| CancelledOrder[View Cancellation]

    WaitingPayment --> CheckPayment{Payment<br/>Received?}
    CheckPayment -->|Yes| UpdateProcessing[Update to Processing]
    CheckPayment -->|No| WaitingPayment

    UpdateProcessing --> ProcessOrder

    ProcessOrder --> PrepareItems[Prepare Items]
    PrepareItems --> PackItems[Pack Items]
    PackItems --> SelectCourier[Select Courier]
    SelectCourier --> GenerateShipping[Generate Shipping Label]

    GenerateShipping --> UpdateShipped[Update Status to Shipped]
    UpdateShipped --> AddTrackingNumber[Add Tracking Number]
    AddTrackingNumber --> NotifyCustomer[Notify Customer via Email]

    NotifyCustomer --> TrackShipment

    TrackShipment --> WaitDelivery{Delivered?}
    WaitDelivery -->|No| TrackShipment
    WaitDelivery -->|Yes| CustomerConfirm{Customer<br/>Confirmed?}

    CustomerConfirm -->|Yes| AutoComplete[Auto Update to Delivered]
    CustomerConfirm -->|No| WaitDelivery

    AutoComplete --> OrderComplete

    OrderComplete --> CheckRefund{Refund<br/>Requested?}
    CheckRefund -->|Yes| ProcessRefund[Review Refund Request]
    CheckRefund -->|No| End([End])

    ProcessRefund --> ApproveRefund{Approve<br/>Refund?}
    ApproveRefund -->|Yes| RefundApproved[Approve & Process Refund]
    ApproveRefund -->|No| RefundRejected[Reject with Reason]

    RefundApproved --> RestoreStock[Restore Product Stock]
    RestoreStock --> ProcessRefundPayment[Process Refund Payment]
    ProcessRefundPayment --> End

    RefundRejected --> End
    CancelledOrder --> End

    classDef startEnd fill:#1a1a1a,stroke:#e0e0e0,stroke-width:2px,color:#ffffff
    classDef success fill:#4caf50,stroke:#388e3c,stroke-width:2px,color:#ffffff
    classDef warning fill:#ff9800,stroke:#f57c00,stroke-width:2px,color:#ffffff
    classDef process fill:#ffffff,stroke:#bdbdbd,stroke-width:1.5px,color:#424242
    classDef decision fill:#f5f5f5,stroke:#bdbdbd,stroke-width:2px,color:#1a1a1a
    
    class Start,End startEnd
    class UpdateProcessing,UpdateShipped,AutoComplete,RefundApproved warning
    class OrderComplete success
    class ViewOrders,ApplyFilter,AllOrders,SelectOrder,ViewOrderDetail,WaitingPayment,ProcessOrder,PrepareItems,PackItems,SelectCourier,GenerateShipping,AddTrackingNumber,NotifyCustomer,TrackShipment,ProcessRefund,RestoreStock,ProcessRefundPayment,RefundRejected,CancelledOrder process
    class FilterOrders,CheckStatus,CheckPayment,WaitDelivery,CustomerConfirm,CheckRefund,ApproveRefund decision
    
    linkStyle default stroke:#757575,stroke-width:1.5px
```

---

## 4. Cart Management Flow

```mermaid
%%{init: {'theme':'base', 'themeVariables': { 'primaryColor':'#ffffff','primaryTextColor':'#1a1a1a','primaryBorderColor':'#e0e0e0','lineColor':'#757575','secondaryColor':'#fafafa','tertiaryColor':'#f5f5f5','background':'#ffffff','fontSize':'14px','fontFamily':'Georgia, serif'}}}%%

flowchart TD
    Start([User Browses Products]) --> ViewProduct[View Product Detail]

    ViewProduct --> AddToCart{Add to<br/>Cart?}
    AddToCart -->|No| Start
    AddToCart -->|Yes| CheckAuth{User<br/>Logged In?}

    CheckAuth -->|No| SessionCart[Add to Session Cart]
    CheckAuth -->|Yes| DatabaseCart[Add to Database Cart]

    SessionCart --> CartAdded[Product Added]
    DatabaseCart --> CartAdded

    CartAdded --> ShowNotification[Show Success Notification]
    ShowNotification --> UpdateCartCount[Update Cart Count Badge]

    UpdateCartCount --> ContinueBrowsing{Continue<br/>Shopping?}
    ContinueBrowsing -->|Yes| Start
    ContinueBrowsing -->|No| ViewCart[Go to Cart Page]

    ViewCart --> CheckCartType{Cart<br/>Type?}
    CheckCartType -->|Session| LoadSession[Load Session Cart Items]
    CheckCartType -->|Database| LoadDB[Load Database Cart Items]

    LoadSession --> ShowItems[Display Cart Items]
    LoadDB --> ShowItems

    ShowItems --> UserAction{User<br/>Action?}

    UserAction -->|Update Quantity| UpdateQty[Update Item Quantity]
    UserAction -->|Remove Item| RemoveItem[Remove from Cart]
    UserAction -->|Clear Cart| ClearCart[Clear All Items]
    UserAction -->|Checkout| SelectItems[Select Items for Checkout]

    UpdateQty --> RecalculateSubtotal[Recalculate Subtotal]
    RemoveItem --> RecalculateSubtotal
    ClearCart --> EmptyCart[Cart is Empty]

    RecalculateSubtotal --> ShowItems

    SelectItems --> ValidateSelection{Items<br/>Selected?}
    ValidateSelection -->|No| ShowError[Show Error: Select Items]
    ValidateSelection -->|Yes| ProceedCheckout[Proceed to Checkout]

    ShowError --> ShowItems
    EmptyCart --> End([End])
    ProceedCheckout --> End

    classDef startEnd fill:#1a1a1a,stroke:#e0e0e0,stroke-width:2px,color:#ffffff
    classDef success fill:#4caf50,stroke:#388e3c,stroke-width:2px,color:#ffffff
    classDef error fill:#f44336,stroke:#d32f2f,stroke-width:2px,color:#ffffff
    classDef process fill:#ffffff,stroke:#bdbdbd,stroke-width:1.5px,color:#424242
    classDef decision fill:#f5f5f5,stroke:#bdbdbd,stroke-width:2px,color:#1a1a1a
    
    class Start,End startEnd
    class CartAdded success
    class ShowError error
    class ViewProduct,SessionCart,DatabaseCart,ShowNotification,UpdateCartCount,ViewCart,LoadSession,LoadDB,ShowItems,UpdateQty,RemoveItem,ClearCart,SelectItems,RecalculateSubtotal,EmptyCart,ProceedCheckout process
    class AddToCart,CheckAuth,ContinueBrowsing,CheckCartType,UserAction,ValidateSelection decision
    
    linkStyle default stroke:#757575,stroke-width:1.5px
```

---

## 5. Product Review Flow

```mermaid
%%{init: {'theme':'base', 'themeVariables': { 'primaryColor':'#ffffff','primaryTextColor':'#1a1a1a','primaryBorderColor':'#e0e0e0','lineColor':'#757575','secondaryColor':'#fafafa','tertiaryColor':'#f5f5f5','background':'#ffffff','fontSize':'14px','fontFamily':'Georgia, serif'}}}%%

flowchart TD
    Start([Customer Views Order]) --> CheckStatus{Order<br/>Delivered?}

    CheckStatus -->|No| CannotReview[Cannot Write Review Yet]
    CheckStatus -->|Yes| ViewProducts[View Order Products]

    ViewProducts --> SelectProduct[Select Product to Review]

    SelectProduct --> CheckExisting{Already<br/>Reviewed?}
    CheckExisting -->|Yes| ViewExisting[View Existing Review]
    CheckExisting -->|No| WriteReview[Write New Review]

    ViewExisting --> EditReview{Edit<br/>Review?}
    EditReview -->|Yes| UpdateReview[Update Review Form]
    EditReview -->|No| End([End])

    WriteReview --> FillRating[Select Star Rating 1-5]
    UpdateReview --> FillRating

    FillRating --> WriteComment[Write Review Comment]
    WriteComment --> SubmitReview[Submit Review]

    SubmitReview --> ValidateInput{Input<br/>Valid?}
    ValidateInput -->|No| ShowValidationError[Show Validation Error]
    ValidateInput -->|Yes| SaveReview[Save Review to Database]

    ShowValidationError --> FillRating

    SaveReview --> ReviewSaved[Review Saved Successfully]
    ReviewSaved --> ShowSuccess[Show Success Message]

    ShowSuccess --> End
    CannotReview --> End

    classDef startEnd fill:#1a1a1a,stroke:#e0e0e0,stroke-width:2px,color:#ffffff
    classDef success fill:#4caf50,stroke:#388e3c,stroke-width:2px,color:#ffffff
    classDef error fill:#f44336,stroke:#d32f2f,stroke-width:2px,color:#ffffff
    classDef process fill:#ffffff,stroke:#bdbdbd,stroke-width:1.5px,color:#424242
    classDef decision fill:#f5f5f5,stroke:#bdbdbd,stroke-width:2px,color:#1a1a1a
    
    class Start,End startEnd
    class ReviewSaved,ShowSuccess success
    class ShowValidationError error
    class ViewProducts,SelectProduct,ViewExisting,WriteReview,UpdateReview,FillRating,WriteComment,SubmitReview,SaveReview,CannotReview process
    class CheckStatus,CheckExisting,EditReview,ValidateInput decision
    
    linkStyle default stroke:#757575,stroke-width:1.5px
```

---

## 6. Stock Management Flow

```mermaid
%%{init: {'theme':'base', 'themeVariables': { 'primaryColor':'#ffffff','primaryTextColor':'#1a1a1a','primaryBorderColor':'#e0e0e0','lineColor':'#757575','secondaryColor':'#fafafa','tertiaryColor':'#f5f5f5','background':'#ffffff','fontSize':'14px','fontFamily':'Georgia, serif'}}}%%

flowchart TD
    Start([Stock Management]) --> Action{Action<br/>Type?}

    Action -->|Order Created| OrderPlaced[Order Placed]
    Action -->|Order Cancelled| OrderCancelled[Order Cancelled]
    Action -->|Refund Approved| RefundApproved[Refund Approved]
    Action -->|Admin Update| AdminUpdate[Admin Stock Update]

    OrderPlaced --> GetOrderItems[Get Order Items]
    GetOrderItems --> LoopItems{For Each<br/>Item}

    LoopItems --> CheckStock{Stock<br/>Available?}
    CheckStock -->|No| InsufficientStock[Stock Insufficient Error]
    CheckStock -->|Yes| ReduceStock[Reduce Product Stock]

    ReduceStock --> NextItem{More<br/>Items?}
    NextItem -->|Yes| LoopItems
    NextItem -->|No| StockUpdated[All Stock Reduced]

    InsufficientStock --> RollbackOrder[Cancel Order Creation]

    OrderCancelled --> GetCancelledItems[Get Cancelled Order Items]
    GetCancelledItems --> RestoreLoop{For Each<br/>Item}
    RestoreLoop --> RestoreStock[Restore Product Stock]
    RestoreStock --> NextRestore{More<br/>Items?}
    NextRestore -->|Yes| RestoreLoop
    NextRestore -->|No| StockRestored[Stock Restored]

    RefundApproved --> GetRefundItems[Get Refunded Items]
    GetRefundItems --> RefundLoop{For Each<br/>Item}
    RefundLoop --> AddStock[Add Back to Stock]
    AddStock --> NextRefund{More<br/>Items?}
    NextRefund -->|Yes| RefundLoop
    NextRefund -->|No| RefundStockUpdated[Refund Stock Updated]

    AdminUpdate --> ManualUpdate[Admin Updates Stock Manually]
    ManualUpdate --> StockChanged[Stock Value Changed]

    StockUpdated --> End([End])
    StockRestored --> End
    RefundStockUpdated --> End
    StockChanged --> End
    RollbackOrder --> End

    classDef startEnd fill:#1a1a1a,stroke:#e0e0e0,stroke-width:2px,color:#ffffff
    classDef success fill:#4caf50,stroke:#388e3c,stroke-width:2px,color:#ffffff
    classDef error fill:#f44336,stroke:#d32f2f,stroke-width:2px,color:#ffffff
    classDef process fill:#ffffff,stroke:#bdbdbd,stroke-width:1.5px,color:#424242
    classDef decision fill:#f5f5f5,stroke:#bdbdbd,stroke-width:2px,color:#1a1a1a
    
    class Start,End startEnd
    class StockUpdated,StockRestored,RefundStockUpdated,StockChanged success
    class InsufficientStock,RollbackOrder error
    class OrderPlaced,OrderCancelled,RefundApproved,AdminUpdate,GetOrderItems,ReduceStock,GetCancelledItems,RestoreStock,GetRefundItems,AddStock,ManualUpdate process
    class Action,LoopItems,CheckStock,NextItem,RestoreLoop,NextRestore,RefundLoop,NextRefund decision
    
    linkStyle default stroke:#757575,stroke-width:1.5px
```

---

**Last Updated:** January 4, 2026  
**System Version:** 1.0.0