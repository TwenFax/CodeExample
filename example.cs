using System;
using System.Collections.Generic;
using System.Text;
using SQLite;
using System.Collections.ObjectModel;
using ************.Database;
using System.Net.Http;
using Xamarin.Forms;
using Newtonsoft.Json;
using System.Threading.Tasks;
using Jdenticon;
using System.IO;
using Grpc.Core;
using Solana;

namespace ************.Models
{
    public static class TLutil
    {
        static readonly string AppPath = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData);
        public static string CreateIdenticon(string s)
        {
            Identicon id = new Identicon(Encoding.ASCII.GetBytes(s), 100);
            string filename = s.GetHashCode().ToString() + ".png";
            id.SaveAsPng(Path.Combine(AppPath, filename));
            return Path.Combine(AppPath, filename);
        }
        public static void CurrentCollectionLoaded()
        {
            App.TLCurrentChain.Owner.Collection.Loadded = true;
        }
        public static void ResetCurrentCollectionLoaded()
        {
            App.TLCurrentChain.Owner.Collection.Loadded = false;
        }
        public static void BlockCurrentCollectionLoading()
        {
            App.TLCurrentChain.Owner.Collection.BlockLoadding = true;
        }
        public static void UnBlockCurrentCollectionLoading()
        {
            App.TLCurrentChain.Owner.Collection.BlockLoadding = false;
        }
        public static bool IsCurrentCollectionLoaded()
        {
            return App.TLCurrentChain.Owner.Collection.Loadded;
        }
        public static bool IsBlockedCurrentCollectionLoading()
        {
            return App.TLCurrentChain.Owner.Collection.BlockLoadding;
        }
    }
    public abstract class TLChain
    {
        [PrimaryKey, AutoIncrement]
        public int Id { get; set; }
        public string Name { get; set; }
        public string Image { get; set; }
        public string Type { get; set; }
        [Ignore]
        public TLOwner Owner { get; set; }
        [Ignore]
        public ObservableCollection<TLOwner> Owners { get; set; }
        public TLChain() { }
        public abstract Task AddOwner(string owner);
        public async Task LoadOwners()
        {
            await NFTDatabase.LoadOwners();
        }
        public abstract Task LoadCollections();
        public abstract Task LoadAssets();
    }
    public class TLOwnerDb
    {
        [PrimaryKey, AutoIncrement]
        public int Id { get; set; }
        public string Owner { get; set; }
        public string Image { get; set; }
        public string Type { get; set; }
        public int FavNumber { get; set; }          // Количество избранных NFT
        public int SavedNumber { get; set; }        // Количество сохранённых NFT
        public string NickName { get; set; }
        public int CollectionsNumber { get; set; }
        public TLOwner ToOwner()
        {
            TLOwner ow = new TLOwner()
            {
                CollectionsNumber = CollectionsNumber,
                FavNumber = FavNumber,
                Id = Id,
                Image = Image,
                NickName = NickName,
                Owner = Owner,
                SavedNumber = SavedNumber,
                Type = Type
            };
            return ow;
        }
    }
    public class TLOwner : BindableObject
    {
        public int Id { get; set; }
        public string Owner { get; set; }
        public string Image { get; set; }
        public string Type { get; set; }
        public int FavNumber { get; set; }          // Количество избранных NFT
        public int SavedNumber { get; set; }        // Количество сохранённых NFT
        private string _nickName;
        public string NickName { get { return _nickName; } set { _nickName = value; OnPropertyChanged(); } }
        public int CollectionsNumber { get; set; }
        public TLCollection Collection { get; set; }
        public ObservableCollection<TLCollection> Collections { get; set; }
        public TLOwnerDb ToDb()
        {
            TLOwnerDb db = new TLOwnerDb()
            {
                CollectionsNumber = CollectionsNumber,
                FavNumber = FavNumber,
                Id = Id,
                Image = Image,
                NickName = NickName,
                Owner = Owner,
                SavedNumber = SavedNumber,
                Type = Type
            };
            return db;
        }
    }
    public class TLCollection
    {
        [PrimaryKey, AutoIncrement]
        public int Id { get; set; }
        public string Name { get; set; }
        public string Descrpt { get; set; }
        public string Slug { get; set; }
        public string Image { get; set; }
        public string Owner { get; set; }
        public int FavNumber { get; set; }          // Количество избранных NFT
        public int SavedNumber { get; set; }        // Количество сохранённых NFT
        public int AssetsNumber { get; set; }
        [Ignore]
        public TLAsset Asset { get; set; }
        [Ignore]
        public ObservableCollection<TLAsset> Assets { get; set; }
        [Ignore]
        public string NextPage { get; set; }
        [Ignore]
        public bool Loadded { get; set; }
        [Ignore]
        public bool BlockLoadding { get; set; }

    }
    public class TLAsset : BindableObject
    {
        private string _anim;

        private string _image;
        public int Id { get; set; }
        public string Image { get { return _image; } set { _image = value; OnPropertyChanged(); } }
        public string BestImage { get; set; }
        public string Anim { get { return _anim; } set { _anim = value; OnPropertyChanged(); } }
        public string Name { get; set; }
        public string Description { get; set; }
        public string CollectionName { get; set; }
        public string AssetOwner { get; set; }
        public string MarketLink { get; set; }
        private bool _favor;
        public bool Favor { get { return _favor; } set { _favor = value; OnPropertyChanged(); } }

        private bool _saved;
        public bool Saved { get { return _saved; } set { _saved = value; OnPropertyChanged(); } }

        private bool _сanAnimate;
        public bool CanAnimate { get { return _сanAnimate; } set { _сanAnimate = value; OnPropertyChanged(); } }
        
        private bool _isAnimated;

        public bool IsAnimated { get { return _isAnimated; } set { _isAnimated = value; OnPropertyChanged(); } }

        private bool _isImage;

        public bool IsImage { get { return _isImage; } set { _isImage = value; OnPropertyChanged(); } }
        private bool _isSvgImage;

        public bool IsSvgImage { get { return _isSvgImage; } set { _isSvgImage = value; OnPropertyChanged(); } }
        public TLAssetDb ToDb()
        {
            TLAssetDb db = new TLAssetDb()
            {
                AssetOwner = AssetOwner,
                CollectionName = CollectionName,
                Description = Description,
                Favor = Favor,
                Id = Id,
                Image = Image,
                BestImage = BestImage,
                Anim = Anim,
                Name = Name,
                MarketLink = MarketLink,
                Saved = Saved
            };
            return db;
        }
    }
    public class TLAssetDb
    {
        [PrimaryKey, AutoIncrement]
        public int Id { get; set; }
        public string Image { get; set; }
        public string BestImage { get; set; }
        public string Anim { get; set; }
        public string Name { get; set; }
        public string Description { get; set; }
        public string CollectionName { get; set; }
        public string AssetOwner { get; set; }
        public string MarketLink { get; set; }
        public bool Favor { get; set; }
        public bool Saved { get; set; }
        public TLAsset ToAsset()
        {
            TLAsset ass = new TLAsset()
            {
                AssetOwner = AssetOwner,
                CollectionName = CollectionName,
                Description = Description,
                Favor = Favor,
                Id = Id,
                Image = Image,
                BestImage = BestImage,
                Anim = Anim,
                Name = Name,
                MarketLink = MarketLink,
                Saved = Saved
            };
            ass.IsSvgImage = ass.BestImage.Contains(".svg");
            ass.IsImage = !ass.IsSvgImage;
            ass.CanAnimate = ass.Anim != null;
            return ass;
        }
    }
    // ETH ----------------------------------------------------------------------------------------------------
    #region ETH
    public class TLEthChain : TLChain
    {
        //public string nextpage { get; set; }
        //public string prevpage { get; set; }
        public TLEthChain()
        {
            Id = 1;
            Name = "Etherium";
            Image = "eth.png";
            Type = "eth";
        }
        public override async Task AddOwner(string owner)
        {
            if (NFTDatabase.Database.Table<TLOwnerDb>().Where(x => x.Owner == owner).ToListAsync().Result.Count > 0)
            {
                await Application.Current.MainPage.DisplayAlert("Owner exist", "", "OK");
                return;
            }
            HttpClient Client = new HttpClient();
            string ResponseBody;
            try
            {
                Client.DefaultRequestHeaders.Add("X-API-Key", "********************************");
                ResponseBody = await Client.GetStringAsync("*****************************************************=" + owner);
                List<TLCollection> collections = JsonConvert.DeserializeObject<List<TLCollection>>(ResponseBody);
                if (collections.Count > 0)
                {
                    TLOwnerDb tLOwner = new TLOwnerDb()
                    {
                        Id = 0,
                        Owner = owner,
                        Image = TLutil.CreateIdenticon(owner),
                        Type = App.TLCurrentChain.Type,
                        FavNumber = 0,
                        SavedNumber = 0,
                        NickName = "",
                        CollectionsNumber = 0
                    };
                    if (App.TLCurrentChain.Owners == null)
                    {
                        App.TLCurrentChain.Owners = new ObservableCollection<TLOwner>();
                    }
                    int res = NFTDatabase.Database.InsertAsync(tLOwner).Result;
                    App.TLCurrentChain.Owners.Add(tLOwner.ToOwner());
                }
                else
                {
                    await Application.Current.MainPage.DisplayAlert("Wallet empty", "", "OK");
                }
            }
            catch (HttpRequestException e)
            {
                await Application.Current.MainPage.DisplayAlert("ETH wallet add error", e.Message, "OK");
            }
        }
        public override async Task LoadCollections()
        {
            HttpClient Client = new HttpClient();
            string ResponseBody;
            try
            {
                string owner = App.TLCurrentChain.Owner.Owner;
                Client.DefaultRequestHeaders.Add("X-API-Key", "********************************");
                ResponseBody = await Client.GetStringAsync("*****************************************?asset_owner=" + owner);
                List<TLEthCollection> collections = JsonConvert.DeserializeObject<List<TLEthCollection>>(ResponseBody);
                foreach (TLEthCollection cl in collections)
                {
                    cl.Image = cl.image_url;
                    cl.Name = cl.name;
                    cl.Slug = cl.slug;
                    cl.Name = cl.name;
                    cl.Id = 0;
                    cl.Owner = owner;
                    cl.SavedNumber = 0;
                    cl.FavNumber = 0;
                    cl.Descrpt = cl.description;
                    cl.AssetsNumber = cl.owned_asset_count;
                }
                if (App.TLCurrentChain.Owner.CollectionsNumber != collections.Count)
                {
                    App.TLCurrentChain.Owner.CollectionsNumber = collections.Count;
                    await NFTDatabase.UpdateCurrentOwner();
                }
                App.TLCurrentChain.Owner.Collections = new ObservableCollection<TLCollection>(collections);
            }
            catch (HttpRequestException e)
            {
                await Application.Current.MainPage.DisplayAlert("ETH collectiom load error", e.Message, "OK");
            }
        }
        public override async Task LoadAssets()
        {
            string owner = App.TLCurrentChain.Owner.Owner;
            string slug = App.TLCurrentChain.Owner.Collection.Slug;
            string collection = App.TLCurrentChain.Owner.Collection.Name;
            int limit = App.PageLength;
            string query;
            if (App.InSavedMode)
            {
                List<TLAssetDb> dbAssets = NFTDatabase.Database.Table<TLAssetDb>().Where(x => x.AssetOwner == owner && x.CollectionName == collection).ToListAsync().Result;
                App.TLCurrentChain.Owner.Collection.Assets = new ObservableCollection<TLAsset>();
                foreach (TLAssetDb db in dbAssets)
                {
                    App.TLCurrentChain.Owner.Collection.Assets.Add(db.ToAsset());
                }
                TLutil.CurrentCollectionLoaded();
                return;
            }
            // ETH
            if (App.TLCurrentChain.Owner.Collection.Assets == null)
            {
                App.TLCurrentChain.Owner.Collection.Assets = new ObservableCollection<TLAsset>();
                query = $"***************************************************************************************";
            }
            else
            {
                if (TLutil.IsCurrentCollectionLoaded() || TLutil.IsBlockedCurrentCollectionLoading())
                    return;
                if (App.TLCurrentChain.Owner.Collection.NextPage == null)
                {
                    TLutil.CurrentCollectionLoaded();
                    return;
                }
                query = $"*********************************************************************************************************************************************";
            }
            HttpClient Client = new HttpClient();
            string ResponseBody;
            try
            {
                TLutil.BlockCurrentCollectionLoading();
                Client.DefaultRequestHeaders.Add("X-API-Key", "01fd68d2c1ad4e059e44b8eb0479cd96");
                await Client.GetStringAsync(query).ContinueWith(x =>
                {
                    ResponseBody = x.Result;
                    TLEthAssetsData httpAssets = JsonConvert.DeserializeObject<TLEthAssetsData>(ResponseBody);
                    foreach (TLEthAsset ass in httpAssets.assets)
                    {
                        ass.AssetOwner = owner;
                        ass.CollectionName = collection;
                        ass.Description = ass.description;
                        ass.Id = 0;
                        ass.Image = ass.image_thumbnail_url;
                        ass.BestImage = ass.image_url.Contains(".svg") ? ass.image_preview_url : ass.image_url;
                        if (ass.animation_url == null || ass.animation_url.Contains(".gif") || ass.animation_url.Contains(".svg"))
                        {
                            ass.Anim = null;
                            ass.CanAnimate = false;
                        }
                        else
                        {
                            ass.Anim = ass.animation_url;
                            ass.CanAnimate = true;
                        }
                        ass.MarketLink = ass.permalink;
                        ass.Name = ass.name;
                        ass.Favor = NFTDatabase.GetAssetFavor(ass);
                        ass.Saved = NFTDatabase.GetAssetSaved(ass);
                        App.TLCurrentChain.Owner.Collection.Assets.Add(ass);
                    }
                    App.TLCurrentChain.Owner.Collection.NextPage = httpAssets.next;
                    if (App.TLCurrentChain.Owner.Collection.NextPage == null)
                        TLutil.CurrentCollectionLoaded();
                    TLutil.UnBlockCurrentCollectionLoading();
                });
            }
            catch (HttpRequestException e)
            {
                await Application.Current.MainPage.DisplayAlert("ETH first page error", e.Message, "OK");
            }
            catch (JsonException e)
            {
                await Application.Current.MainPage.DisplayAlert("Json Error", e.Message, "OK");
            }
        }
    }
    public class TLEthCollection : TLCollection
    {
        public string name { get; set; }
        public string description { get; set; }
        public string image_url { get; set; }
        public string animation_url { get; set; }
        public string slug { get; set; }
        public int owned_asset_count { get; set; }
        public TLEthCollection()
        {
            Name = name;
            Descrpt = description;
            Image = image_url;
            Slug = slug;
            Id = 0;
            Owner = null;
            FavNumber = 0;
            SavedNumber = 0;
            AssetsNumber = 0;
        }
    }
    public class TLEthAsset : TLAsset
    {
        public string id { get; set; }
        public string image_thumbnail_url { get; set; }
        public string image_url { get; set; }
        public string image_preview_url { get; set; }
        public string animation_url { get; set; }
        public string name { get; set; }
        public string description { get; set; }
        public string permalink { get; set; }
        public TLEthAsset()
        {
            Name = name;
            Description = description;
            Image = image_thumbnail_url;
            BestImage = image_url;
            Anim = animation_url;
            CollectionName = App.TLCurrentChain.Owner.Collection.Name;
            AssetOwner = App.TLCurrentChain.Owner.Owner;
            MarketLink = permalink;
            Favor = false;
            Saved = false;
        }
    }
    public class TLEthAssetsData
    {
        public string next { get; set; }
        public string previous { get; set; }
        public List<TLEthAsset> assets { get; set; }
    }
    #endregion ETH
    // WAX ----------------------------------------------------------------------------------------------------
    #region WAX
    public class TLWaxChain : TLChain
    {
        public TLWaxChain()
        {
            Id = 2;
            Name = "WAX";
            Image = "wax.png";
            Type = "wax";
        }
        public class TLWaxCollection : TLCollection
        {
            public string name { get; set; }
            public string description { get; set; }
            public string image_url { get; set; }
            public string slug { get; set; }
            public TLWaxCollection()
            {
                Name = name;
                Descrpt = description;
                Image = image_url;
                Slug = slug;
                Id = 0;
                Owner = null;
                FavNumber = 0;
                SavedNumber = 0;
                AssetsNumber = 0;
            }
        }
        public override async Task AddOwner(string owner)
        {
            if (NFTDatabase.Database.Table<TLOwnerDb>().Where(x => x.Owner == owner).ToListAsync().Result.Count > 0)
            {
                await Application.Current.MainPage.DisplayAlert("WAX Owner exist", "", "OK");
                return;
            }
            HttpClient Client = new HttpClient();
            string ResponseBody;
            try
            {
                string query = "*********************************************************" + owner;
                ResponseBody = await Client.GetStringAsync(query);
                WaxCollData data = JsonConvert.DeserializeObject<WaxCollData>(ResponseBody);
                if (data.data.collections.Count > 0)
                {
                    TLOwnerDb tLOwnerDb = new TLOwnerDb()
                    {
                        Id = 0,
                        Owner = owner,
                        Image = TLutil.CreateIdenticon(owner),
                        Type = App.TLCurrentChain.Type,
                        FavNumber = 0,
                        SavedNumber = 0,
                        NickName = "",
                        CollectionsNumber = data.data.collections.Count
                    };
                    if (App.TLCurrentChain.Owners == null)
                    {
                        App.TLCurrentChain.Owners = new ObservableCollection<TLOwner>();
                    }
                    int res = NFTDatabase.Database.InsertAsync(tLOwnerDb).Result;
                    App.TLCurrentChain.Owners.Add(tLOwnerDb.ToOwner());
                }
                else
                {
                    await Application.Current.MainPage.DisplayAlert("WAX Wallet empty", "", "OK");
                }
            }
            catch (HttpRequestException e)
            {
                await Application.Current.MainPage.DisplayAlert("WAX wallet add error", e.Message, "OK");
            }
        }
        public override async Task LoadCollections()
        {
            HttpClient Client = new HttpClient();
            string ResponseBody;
            try
            {
                string owner = App.TLCurrentChain.Owner.Owner;
                string query = "*********************************************************" + owner;
                ResponseBody = await Client.GetStringAsync(query);
                WaxCollData data = JsonConvert.DeserializeObject<WaxCollData>(ResponseBody);
                if (App.TLCurrentChain.Owner.Collections == null)
                    App.TLCurrentChain.Owner.Collections = new ObservableCollection<TLCollection>();
                else
                    App.TLCurrentChain.Owner.Collections.Clear();
                foreach (Collection cl in data.data.collections)
                {
                    cl.collection.Image = "*******************************" + cl.collection.img;
                    cl.collection.Name = cl.collection.name;
                    cl.collection.Slug = cl.collection.collection_name;
                    cl.collection.Name = cl.collection.name;
                    cl.collection.Id = 0;
                    cl.collection.Owner = owner;
                    cl.collection.SavedNumber = 0;
                    cl.collection.FavNumber = 0;
                    cl.collection.Descrpt = "";
                    cl.collection.AssetsNumber = cl.assets;
                    App.TLCurrentChain.Owner.Collections.Add(cl.collection);
                }
            }
            catch (HttpRequestException e)
            {
                await Application.Current.MainPage.DisplayAlert("WAX collectiom load error", e.Message, "OK");
            }
        }
        public override async Task LoadAssets()
        {
            string owner = App.TLCurrentChain.Owner.Owner;
            string slug = App.TLCurrentChain.Owner.Collection.Slug;
            string collection = App.TLCurrentChain.Owner.Collection.Name;
            int limit = App.PageLength;
            int page;
            if (App.InSavedMode)
            {
                List<TLAssetDb> dbAssets = NFTDatabase.Database.Table<TLAssetDb>().Where(x => x.AssetOwner == owner && x.CollectionName == collection).ToListAsync().Result;
                App.TLCurrentChain.Owner.Collection.Assets = new ObservableCollection<TLAsset>();
                foreach (TLAssetDb db in dbAssets)
                {
                    App.TLCurrentChain.Owner.Collection.Assets.Add(db.ToAsset());
                }
                TLutil.CurrentCollectionLoaded();
                return;
            }
            // WAX
            //ObservableCollection<TLAsset> assets = App.TLCurrentChain.Owner.Collection.Assets;
            if (TLutil.IsCurrentCollectionLoaded() || TLutil.IsBlockedCurrentCollectionLoading())
                return;
            if (App.TLCurrentChain.Owner.Collection.Assets == null)
            {
                page = 1;
                App.TLCurrentChain.Owner.Collection.Assets = new ObservableCollection<TLAsset>();
            }
            else
            {
                page = (App.TLCurrentChain.Owner.Collection.Assets.Count / App.PageLength) + 1;
            }
            HttpClient Client = new HttpClient();
            string ResponseBody;
            try
            {
                string query = $"******************************************************?collection_name={slug}&owner={owner}&page={page}&limit={limit}&order=asc&sort=name";
                ResponseBody = await Client.GetStringAsync(query);
                WaxAssetData waxassets = JsonConvert.DeserializeObject<WaxAssetData>(ResponseBody);
                foreach (var ass in waxassets.data)
                {
                    ass.AssetOwner = owner;
                    ass.CollectionName = collection;
                    ass.Description = ass.data.description;
                    ass.Id = 0;
                    if (ass.data.img == null)
                    {
                        if (ass.data.video != null)
                        {
                            ass.Image = "video.png";
                            ass.Anim = "******************" + ass.data.video;
                        }
                    }
                    else
                        ass.Image = "***************" + ass.data.img;
                    ass.BestImage = ass.Image;
                    ass.Name = ass.data.name;
                    ass.Favor = NFTDatabase.GetAssetFavor(ass);
                    ass.Saved = NFTDatabase.GetAssetSaved(ass);
                    App.TLCurrentChain.Owner.Collection.Assets.Add(ass);
                }
                if (App.TLCurrentChain.Owner.Collection.Assets.Count == App.TLCurrentChain.Owner.Collection.AssetsNumber)
                    TLutil.CurrentCollectionLoaded();
            }
            catch (HttpRequestException e)
            {
                await Application.Current.MainPage.DisplayAlert("WAX first page error", e.Message, "OK");
            }
            catch (JsonException e)
            {
                await Application.Current.MainPage.DisplayAlert("Json Error", e.Message, "OK");
            }
        }
    }
    public class WaxCollData
    {
        public bool success { get; set; }
        public CollData data { get; set; }
    }
    public class WaxAssetData
    {
        public bool success { get; set; }
        public List<AssetData> data { get; set; }
    }
    public class CollData
    {
        public List<Collection> collections { get; set; }
        public int assets { get; set; }
    }
    public class AssetData : TLAsset
    {
        public InnerData data;
    }
    public class InnerData
    {
        public string name { get; set; }
        public string description { get; set; }
        public string img { get; set; }
        public string video { get; set; }
    }
    public class Collection2 : TLCollection
    {
        public string collection_name { get; set; }
        public string name { get; set; }
        public string img { get; set; }
    }
    public class Collection
    {
        public Collection2 collection { get; set; }
        public int assets { get; set; }
    }
    #endregion WAX
    // SOLANA -------------------------------------------------------------------------------------------------
    #region SOLANA
    public class TLSolChain : TLChain
    {
        private static readonly string host = "************";
        private static readonly int port = ******;
        private static string caCrt;
        private static string clientCrt;
        private static string clientKey;
        public TLSolChain()
        {
            Id = 3;
            Name = "Solana";
            Image = "sol.png";
            Type = "sol";
            System.Reflection.Assembly assembly = System.Reflection.IntrospectionExtensions.GetTypeInfo(typeof(TokensPageViewModel)).Assembly;
            Stream stream = assembly.GetManifestResourceStream("************.Crt.ca.crt");
            caCrt = "";
            using (StreamReader reader = new System.IO.StreamReader(stream))
            {
                caCrt = reader.ReadToEnd();
            }
            stream.Dispose();
            stream = assembly.GetManifestResourceStream("************.Crt.client.crt");
            using (StreamReader reader = new System.IO.StreamReader(stream))
            {
                clientCrt = reader.ReadToEnd();
            }
            stream.Dispose();
            stream = assembly.GetManifestResourceStream("************.Crt.client.key");
            using (StreamReader reader = new System.IO.StreamReader(stream))
            {
                clientKey = reader.ReadToEnd();
            }
            stream.Dispose();
        }
        private async Task<CollectionRsp> AskCollections(string owner)
        {
            KeyCertificatePair keyCertificatePair = new KeyCertificatePair(clientCrt, clientKey);
            SslCredentials sslCredentials = new SslCredentials(caCrt, keyCertificatePair);
            CallCredentials callCredentials = CallCredentials.FromInterceptor((context, metadata) => Task.CompletedTask);
            ChannelCredentials channelCredentials = ChannelCredentials.Create(sslCredentials, callCredentials);
            Channel channel = new Channel(host, port, channelCredentials);
            SolanaApi.SolanaApiClient client = new SolanaApi.SolanaApiClient(channel);
            CollectionRsp replay = null;
            try
            {
                CollectionRsp tResp = await client.GetCollectionsAsync(new CollectionReq { Wallet = owner });
                try
                {
                    replay = tResp;
                }
                catch (System.AggregateException e)
                {
                    await Application.Current.MainPage.DisplayAlert(e.Message, "", "OK");
                }
            }
            catch (Grpc.Core.RpcException e)
            {
                Console.WriteLine("Solana server error:" + e.StatusCode);
            }
            _ = channel.ShutdownAsync();
            return replay;
        }
        private async Task<TokensFromCollectionResp> AskTokens(string owner, string collection)
        {
            KeyCertificatePair keyCertificatePair = new KeyCertificatePair(clientCrt, clientKey);
            SslCredentials sslCredentials = new SslCredentials(caCrt, keyCertificatePair);
            CallCredentials callCredentials = CallCredentials.FromInterceptor((context, metadata) => Task.CompletedTask);
            ChannelCredentials channelCredentials = ChannelCredentials.Create(sslCredentials, callCredentials);
            Channel channel = new Channel(host, port, channelCredentials);
            SolanaApi.SolanaApiClient client = new SolanaApi.SolanaApiClient(channel);
            TokensFromCollectionResp replay = null;
            try
            {
                AsyncUnaryCall<TokensFromCollectionResp> tResp = client.GetTokensFromCollectionAsync(new TokensFromCollectionReq { Wallet = owner, CollectionName = collection });
                try
                {
                    replay = tResp.ResponseAsync.Result;
                }
                catch (System.AggregateException e)
                {
                    await Application.Current.MainPage.DisplayAlert(e.Message, "", "OK");
                }
            }
            catch (Grpc.Core.RpcException e)
            {
                Console.WriteLine("Solana server error:" + e.StatusCode);
            }
            _ = channel.ShutdownAsync();
            return replay;
        }
        public override async Task AddOwner(string owner)
        {
            if (NFTDatabase.Database.Table<TLOwnerDb>().Where(x => x.Owner == owner).ToListAsync().Result.Count > 0)
            {
                await Application.Current.MainPage.DisplayAlert("Solana Owner exist", "", "OK");
                return;
            }
            CollectionRsp replay = await AskCollections(owner);
            if (replay == null)
            {
                await Application.Current.MainPage.DisplayAlert("Solana Owner error", "", "OK");
                return;
            }

            if (replay.Collections.Count < 1)
            {
                await Application.Current.MainPage.DisplayAlert("Solana Owner empty", "", "OK");
                return;
            }
            TLOwnerDb tlOwnerDb = new TLOwnerDb()
            {
                Id = 0,
                Owner = owner,
                Image = TLutil.CreateIdenticon(owner),
                Type = App.TLCurrentChain.Type,
                FavNumber = 0,
                SavedNumber = 0,
                NickName = "",
                CollectionsNumber = replay.Collections.Count,
            };
            if (App.TLCurrentChain.Owners == null)
            {
                App.TLCurrentChain.Owners = new ObservableCollection<TLOwner>();
            }
            int res = NFTDatabase.Database.InsertAsync(tlOwnerDb).Result;
            App.TLCurrentChain.Owners.Add(tlOwnerDb.ToOwner());
            foreach (TLOwner ow in App.TLCurrentChain.Owners)
            {
                if (ow.Owner == owner)
                {
                    App.TLCurrentChain.Owner = ow;
                    break;
                }
            }
            parsCollectionRequest(replay);
        }
        public override async Task LoadCollections()
        {
            string owner = App.TLCurrentChain.Owner.Owner;

            CollectionRsp replay = await AskCollections(owner);
            if (replay == null)
                return;
            parsCollectionRequest(replay);
        }
        public override async Task LoadAssets()
        {
            string owner = App.TLCurrentChain.Owner.Owner;
            string slug = App.TLCurrentChain.Owner.Collection.Slug;
            string collection = App.TLCurrentChain.Owner.Collection.Name;
            int limit = App.PageLength;
            int page;
            if (App.InSavedMode)
            {
                List<TLAssetDb> dbAssets = NFTDatabase.Database.Table<TLAssetDb>().Where(x => x.AssetOwner == owner && x.CollectionName == collection).ToListAsync().Result;
                App.TLCurrentChain.Owner.Collection.Assets = new ObservableCollection<TLAsset>();
                foreach (TLAssetDb db in dbAssets)
                {
                    App.TLCurrentChain.Owner.Collection.Assets.Add(db.ToAsset());
                }
                TLutil.CurrentCollectionLoaded();
                return;
            }
            // SOL
            ObservableCollection<TLAsset> assets = App.TLCurrentChain.Owner.Collection.Assets;
            if (TLutil.IsCurrentCollectionLoaded() || TLutil.IsBlockedCurrentCollectionLoading())
                return;
            if (App.TLCurrentChain.Owner.Collection.Assets == null)
            {
                page = 1;
                App.TLCurrentChain.Owner.Collection.Assets = new ObservableCollection<TLAsset>();
            }
            else
            {
                page = (assets.Count / App.PageLength) + 1;
            }
            TokensFromCollectionResp replay = await AskTokens(owner, collection);
            foreach (var ass in replay.Tokens)
            {
                TLAsset asset = new TLAsset()
                {
                    AssetOwner = owner,
                    CollectionName = collection,
                    Description = ass.Discription,
                    Id = 0,
                    Image = ipfs01(ass.Link),
                };
                asset.BestImage = asset.Image;
                asset.Favor = NFTDatabase.GetAssetFavor(asset);
                asset.Saved = NFTDatabase.GetAssetSaved(asset);
                App.TLCurrentChain.Owner.Collection.Assets.Add(asset);
            }
            if (App.TLCurrentChain.Owner.Collection.Assets.Count == App.TLCurrentChain.Owner.Collection.AssetsNumber)
                TLutil.CurrentCollectionLoaded();
        }
        private void parsCollectionRequest(CollectionRsp replay)
        {
            App.TLCurrentChain.Owner.Collections = new ObservableCollection<TLCollection>();
            foreach (Solana.Collection col in replay.Collections)
            {
                TLCollection cl = new TLCollection()
                {
                    Name = col.Name,
                    Descrpt = col.Discription,
                    Image = ipfs01(col.Image),
                    AssetsNumber = (int)col.NumberOfTokens
                };
                App.TLCurrentChain.Owner.Collections.Add(cl);
            }
        }
        private string ipfs01(string link)
        {
            string s;
            var i = link.IndexOf("?ext");
            if (i > 0)
                s = link.Remove(i);
            else
                s = link;
            if (s.Contains(".ipfs.dweb.link"))
            {
                s = s.Replace("*************", "");
                s = s.Replace(@"********", @"*********************");
            }
            return s;
        }
    }
    #endregion
}